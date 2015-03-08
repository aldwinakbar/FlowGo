package com.truibot.flowgo;

import java.util.Collections;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

public class InitActivity extends Activity {
    private Button button_login;
    private EditText usernameUI, passwordUI;
    private TextView warning;
    private Context context = this;
    private int count=0;
    private static final String userId="johny";
    private static final String password="12345";
    public static final String MyPREFERENCES = "flowgoes" ;
    SharedPreferences sharedpreferences;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_init);
            usernameUI = (EditText) findViewById(R.id.login_edittext_username);
            passwordUI = (EditText) findViewById(R.id.login_edittext_password);
            usernameUI.addTextChangedListener(mTextEditorWatcher);
            passwordUI.addTextChangedListener(mTextEditorWatcher);
            warning =(TextView) findViewById(R.id.login_warning);
            warning.setVisibility(View.GONE);
            button_login = (Button)findViewById(R.id.login_button);
    }

    @Override
    protected void onResume() {
        sharedpreferences=getSharedPreferences(MyPREFERENCES,
                Context.MODE_PRIVATE);
        if (sharedpreferences.contains(userId))
        {
            if(sharedpreferences.contains(password)){
                goNext();
            }
        }
        super.onResume();
    }

    public void login(View view){
        SharedPreferences.Editor editor = sharedpreferences.edit();
        String u = usernameUI.getText().toString();
        String p = passwordUI.getText().toString();
        editor.putString(userId, u);
        editor.putString(password, p);
        editor.commit();
        goNext();
    }

    private void goNext(){
        Intent i = new Intent(this,MainActivity.class);
        startActivity(i);
    }

    private final TextWatcher  mTextEditorWatcher = new TextWatcher() {

        public void beforeTextChanged(CharSequence s, int start, int count, int after)
        {
            // When No Password Entered
            button_login.setEnabled(false);
            button_login.setBackgroundColor(context.getResources().getColor(R.color.material_blue_grey_500));
        }

        public void onTextChanged(CharSequence s, int start, int before, int count)
        {

        }

        public void afterTextChanged(Editable s)
        {
            checkFieldsForEmptyValues();
        }
    };

    private void checkFieldsForEmptyValues(){

        String s1 = usernameUI.getText().toString();
        String s2 = passwordUI.getText().toString();

        if(s1.equals("")|| s2.equals("")){
            button_login.setEnabled(false);
            button_login.setBackgroundColor(context.getResources().getColor(R.color.material_blue_grey_500));
        } else {
            button_login.setEnabled(true);
            button_login.setBackgroundColor(context.getResources().getColor(R.color.material_red_700));
        }
    }


}