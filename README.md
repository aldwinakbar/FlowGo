# FlowGo
How much do you use water daily?
How much does this city (jakarta) use daily?
What is the proportion of water usage from different sources that is ground-water and PDAM?

We propose the FlowGo, a simple 8-bit microcontroller that is connected to the internet through a GSM module and monitors volume of consumed water through a flow sensor. The FlowGo is both reliable and sustainable because it  uses a  microhydro generator to charge its batteries.

On the server side, each and every FlowGo will post data of volume consumed and battery every time sample. The consumed water will be summed at the server-end.

FlowGo will benefit personally through the android app that monitors personal consumption as well as due date to pay if the user uses PDAM.

On the large scale, FlowGo will benefit government institutions, bussinesses as well as anyone interested in hydrogeological subjects. With te collective data, trends of consumption can be studied and relevant actions can be taken for the greater good. 

Parts Needed :

| Microcontroller working in 3.3V | Micro-hydro Generator | Water Flow Sensor G1/2 | GSM Module |

We are T-Bot-PRJ consisting of 5 people who want to make a difference in the country and the world.


Folder directory:

					
 | /flowgo-arduino			: Source code for the arduino. 		|  
 | /FlowGo				: Source code for the android app 	| 
 | /flowgo-server			: Source code for the server		|
