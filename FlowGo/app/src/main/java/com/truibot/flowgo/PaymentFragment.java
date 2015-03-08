package com.truibot.flowgo;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import org.achartengine.ChartFactory;

import static android.view.View.*;

/**
 * Created by Darwinx on 3/8/2015.
 */
public class PaymentFragment extends Fragment {
    TextView text1,text2,text3,text4;
    Button paid;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_payment, container, false);
        LinearLayout chartContainer = (LinearLayout) rootView.findViewById( R.id.chart_container);
        paid = (Button)rootView.findViewById(R.id.button_payment);
        text1 = (TextView)rootView.findViewById(R.id.payment_notice);
        text2 = (TextView)rootView.findViewById(R.id.payment_usage);
        text3 = (TextView)rootView.findViewById(R.id.payment_period);

        paid.setOnClickListener(new OnClickListener() {
           @Override
            public void onClick(View v) {
                   text2.setText("You have paid your bill this period");
                   text1.setVisibility(INVISIBLE);
                  text3.setVisibility(INVISIBLE);
                   paid.setVisibility(INVISIBLE);
            }
        });

        return rootView;
    }
}
