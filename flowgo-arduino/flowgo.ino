/*************************************************** 
  This is an example for our Adafruit FONA Cellular Module

  Designed specifically to work with the Adafruit FONA 
  ----> http://www.adafruit.com/products/1946
  ----> http://www.adafruit.com/products/1963

  These displays use TTL Serial to communicate, 2 pins are required to 
  interface
  Adafruit invests time and resources providing this open source code, 
  please support Adafruit and open-source hardware by purchasing 
  products from Adafruit!

  Written by Limor Fried/Ladyada for Adafruit Industries.  
  BSD license, all text above must be included in any redistribution
 ****************************************************/

/* 
THIS CODE IS STILL IN PROGRESS!

Open up the serial console on the Arduino at 115200 baud to interact with FONA

Note that if you need to set a GPRS APN, username, and password scroll down to
the commented section below at the end of the setup() function.

*/

#include <SoftwareSerial.h>
#include "Adafruit_FONA.h"

#define FONA_RX 5
#define FONA_TX 3
#define FONA_RST 4
#define interruptPin 2


volatile int inputState;
float consumedVolume;
long tickNow, tickOld;
long stateOld,stateNow;
// this is a large buffer for replies
char replybuffer[255];

// or comment this out & use a hardware serial port like Serial1 (see below)
SoftwareSerial fonaSS = SoftwareSerial(FONA_TX, FONA_RX);

Adafruit_FONA fona = Adafruit_FONA(FONA_RST);

uint8_t readline(char *buff, uint8_t maxbuff, uint16_t timeout = 0);


long interval = 1000;
void setup() {
  
  
  pinMode(2,INPUT);
  attachInterrupt(0, flowMeter_Interrupt, RISING);
  inputState = 0;
  
  while (!Serial);

  Serial.begin(115200);
  Serial.println(F("FONA basic test"));
  Serial.println(F("Initializing....(May take 3 seconds)"));

  // make it slow so its easy to read!
  fonaSS.begin(4800); // if you're using software serial
  //Serial1.begin(4800); // if you're using hardware serial

  // See if the FONA is responding
  if (! fona.begin(fonaSS)) {           // can also try fona.begin(Serial1) 
    Serial.println(F("Couldn't find FONA"));
    while (1);
  }
  Serial.println(F("FONA is OK"));

  // Print SIM card IMEI number.
  char imei[15] = {0}; // MUST use a 16 character buffer for IMEI!
  uint8_t imeiLen = fona.getIMEI(imei);
  if (imeiLen > 0) {
    Serial.print("SIM card IMEI: "); Serial.println(imei);
  }

  // Optionally configure a GPRS APN, username, and password.
  // You might need to do this to access your network's GPRS/data
  // network.  Contact your provider for the exact APN, username,
  // and password values.  Username and password are optional and
  // can be removed, but APN is required.
  fona.setGPRSNetworkSettings(F("indosatgprs"), F(""), F(""));

  // Optionally configure HTTP gets to follow redirects over SSL.
  // Default is not to follow SSL redirects, however if you uncomment
  // the following line then redirects over SSL will be followed.
  //fona.setHTTPSRedirect(true);
  delay(14000);
       if (!fona.enableGPRS(true))  
         Serial.println(F("Failed to turn on"));
       else
         Serial.println(F("GPRS Data On"));

        if (!fona.enableNTPTimeSync(true, F("pool.ntp.org")))
        Serial.println(F("Failed to enable NTP Time Sync"));
        else
         Serial.println(F("NTP Time Sync On"));
        
        if (!fona.enableNetworkTimeSync(true))
        Serial.println(F("Failed to enable Network Time Sync"));
        else
         Serial.println(F("Network Time Sync On"));
}

int flagSend = 0;
void loop() {

      // read the time
      char bufferNTP[23];     
     
      fona.getTime(bufferNTP, 23);  // make sure replybuffer is at least 23 bytes!
      Serial.print(F("Time = "));Serial.println(bufferNTP); 
      int minute = ((bufferNTP[16] - '0')*10) + (bufferNTP[17] - '0');
      Serial.println(minute); 
      
      if(minute != 16 && flagSend == 1){flagSend = 0;}  
      if(minute == 16 && flagSend == 0){  
      
      
      int id= 1;           // FlowGo Identification Number 
       
      int flow=inputState;      // Flow Reading
      uint16_t batt;       // Battery Reading
        if (! fona.getBattPercent(&batt)) {
          Serial.println(F("Failed to read Batt"));
        } else {
          Serial.print(F("VPct = ")); Serial.print(batt); Serial.println(F("%"));
        }        
        
      //identification integer to char
      char idData[4];
      String strId;
      strId=String(id);
      strId.toCharArray(idData,4);  
      char idPost[6] = "id=";  
    
      //flow integer to char
      char flowData[6];
      String strFlow;
      strFlow=String(flow);
      strFlow.toCharArray(flowData,6);  
      char flowPost[7] = "&flow=";  
  
      //batt integer to char      
      char battData[4];
      String strBatt;
      strBatt=String(batt);
      strBatt.toCharArray(battData,4);  
      char battPost[7] = "&batt=";  
  
    
      char data[80];
      strcpy (data,idPost);
      strcat (data,idData);
      strcat (data,flowPost);
      strcat (data,flowData);
      strcat (data,battPost);
      strcat (data,battData);
      
      // Post data to website
      uint16_t statuscode;
      int16_t length;
      char url[80] = "77cb59e5.ngrok.com/flowgo/receiver.php";
      
      
      
      flushSerial();
     // Serial.print(F("http://")); readline(url, 79);
     // Serial.println(url);
      Serial.println(F("Data to post (e.g. \"foo\" or \"{\"simple\":\"json\"}\"):"));
     // readline(data, 79);
      Serial.println(data);
      
       Serial.println(F("****"));
       if (!fona.HTTP_POST_start(url, F("application/x-www-form-urlencoded"), (uint8_t *) data, strlen(data), &statuscode, (uint16_t *)&length)) {
         Serial.println("Failed!");
         
       }
       while (length > 0) {
         while (fona.available()) {
           char c = fona.read();
           
#if defined(__AVR_ATmega328P__) || defined(__AVR_ATmega168__)
           loop_until_bit_is_set(UCSR0A, UDRE0); /* Wait until data register empty. */
           UDR0 = c;
#else
           Serial.write(c);
#endif
           
           length--;
           if (! length) break;
         }
       }
       Serial.println(F("\n****"));
       fona.HTTP_POST_end();


  // flush input
  flushSerial();
  while (fona.available()) {
    Serial.write(fona.read());
  }

  flagSend = 1;  
  inputState = 0;
    }//if end  
}




void flushSerial() {
    while (Serial.available()) 
    Serial.read();
}

char readBlocking() {
  while (!Serial.available());
  return Serial.read();
}
uint16_t readnumber() {
  uint16_t x = 0;
  char c;
  while (! isdigit(c = readBlocking())) {
    //Serial.print(c);
  }
  Serial.print(c);
  x = c - '0';
  while (isdigit(c = readBlocking())) {
    Serial.print(c);
    x *= 10;
    x += c - '0';
  }
  return x;
}
  
uint8_t readline(char *buff, uint8_t maxbuff, uint16_t timeout) {
  uint16_t buffidx = 0;
  boolean timeoutvalid = true;
  if (timeout == 0) timeoutvalid = false;
  
  while (true) {
    if (buffidx > maxbuff) {
      //Serial.println(F("SPACE"));
      break;
    }

    while(Serial.available()) {
      char c =  Serial.read();

      //Serial.print(c, HEX); Serial.print("#"); Serial.println(c);

      if (c == '\r') continue;
      if (c == 0xA) {
        if (buffidx == 0)   // the first 0x0A is ignored
          continue;
        
        timeout = 0;         // the second 0x0A is the end of the line
        timeoutvalid = true;
        break;
      }
      buff[buffidx] = c;
      buffidx++;
    }
    
    if (timeoutvalid && timeout == 0) {
      //Serial.println(F("TIMEOUT"));
      break;
    }
    delay(1);
  }
  buff[buffidx] = 0;  // null term
  return buffidx;
}

void flowMeter_Interrupt()
{
  inputState++;
}
