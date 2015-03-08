package com.truibot.flowgo;

/**
 * Created by Darwinx on 3/7/2015.
 */

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import org.achartengine.ChartFactory;
import org.achartengine.chart.PointStyle;
import org.achartengine.model.XYMultipleSeriesDataset;
import org.achartengine.model.XYSeries;
import org.achartengine.renderer.XYMultipleSeriesRenderer;
import org.achartengine.renderer.XYSeriesRenderer;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

/**
 * A fragment that launches other parts of the demo application.
 */
public class GraphFragment extends Fragment {
    private static final String SERVICE_URL = "http://77cb59e5.ngrok.com/flowgo/out.php";
    private static final String TAG = "MainActivity";

    private String[] mMonth = new String[] {
            "Jan", "Feb" , "Mar", "Apr", "May", "Jun",
           "Jul", "Aug" , "Sep", "Oct", "Nov", "Dec"
    };

    //private ArrayList<String>month =new ArrayList<>();
    //private ArrayList<String>usages = new ArrayList<>();
    private XYMultipleSeriesDataset dataset = new XYMultipleSeriesDataset();
    private XYMultipleSeriesRenderer multiRenderer = new XYMultipleSeriesRenderer();

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_graph, container, false);
        LinearLayout chartContainer = (LinearLayout) rootView.findViewById( R.id.chart_container);

        openChart();
        View mChartView = ChartFactory.getLineChartView(getActivity(), dataset, multiRenderer);
        chartContainer.addView(mChartView);
        return rootView;
    }

    private void openChart(){
        int[] x = { 1,2,3,4,5,6,7,8 };
        int[] Usage = { 2000,2500,2700,3000,2800,3500,3700,3800};

        XYSeries UsageSeries = new XYSeries("Usage");

        for(int i=0;i<Usage.length;i++){
           // UsageSeries.add(i+1, Integer.parseInt(usages.get(i)));
            UsageSeries.add(i+1, Usage[i]);
        }


        dataset.addSeries(UsageSeries);

        XYSeriesRenderer UsageRenderer = new XYSeriesRenderer();
        UsageRenderer.setColor(getResources().getColor(R.color.material_light_blue_500));
        UsageRenderer.setPointStyle(PointStyle.CIRCLE);
        UsageRenderer.setFillPoints(true);
        UsageRenderer.setLineWidth(2);
        UsageRenderer.setDisplayChartValues(true);


        multiRenderer.setXLabels(0);
        multiRenderer.setChartTitle("Water Usage");
        multiRenderer.setXTitle("Time Stamp");
        multiRenderer.setYTitle("Debit");
        multiRenderer.setZoomButtonsVisible(true);
        for(int i=0;i<Usage.length;i++){
            //Date date = new Date(month.get(i));
            //SimpleDateFormat f = new SimpleDateFormat("Y/M/d H:m:s");
            //String dateFormatted = f.format(date);
            //multiRenderer.addXTextLabel(i+1, dateFormatted);
            multiRenderer.addTextLabel(i+1,mMonth[i]);
        }

        multiRenderer.addSeriesRenderer(UsageRenderer);
        multiRenderer.setApplyBackgroundColor(true);
        multiRenderer.setBackgroundColor(getResources().getColor(R.color.pure_white));
        multiRenderer.setMarginsColor(getResources().getColor(R.color.pure_white));

    }

    public void handleResponse(String response) {

        try {
        } catch (Exception e) {
            Log.e(TAG, e.getLocalizedMessage(), e);

        }

    }

    private class WebServiceTask extends AsyncTask<String, Integer, String> {

        public static final int POST_TASK = 1;
        public static final int GET_TASK = 2;

        private static final String TAG = "WebServiceTask";

        // connection timeout, in milliseconds (waiting to connect)
        private static final int CONN_TIMEOUT = 3000;

        // socket timeout, in milliseconds (waiting for data)
        private static final int SOCKET_TIMEOUT = 5000;

        private int taskType = GET_TASK;
        private Context mContext = null;
        private String processMessage = "Processing...";

        private ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();

        private ProgressDialog pDlg = null;

        public WebServiceTask(int taskType, Context mContext, String processMessage) {

            this.taskType = taskType;
            this.mContext = mContext;
            this.processMessage = processMessage;
        }

        public void addNameValuePair(String name, String value) {

            params.add(new BasicNameValuePair(name, value));
        }

        private void showProgressDialog() {

            pDlg = new ProgressDialog(mContext);
            pDlg.setMessage(processMessage);
            pDlg.setProgressDrawable(mContext.getWallpaper());
            pDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            pDlg.setCancelable(false);
            pDlg.show();

        }

        @Override
        protected void onPreExecute() {
            showProgressDialog();

        }

        protected String doInBackground(String... urls) {

            String url = urls[0];
            String result = "";

            HttpResponse response = doResponse(url);

            if (response == null) {
                return result;
            } else {

                try {

                    result = inputStreamToString(response.getEntity().getContent());

                } catch (IllegalStateException e) {
                    Log.e(TAG, e.getLocalizedMessage(), e);

                } catch (IOException e) {
                    Log.e(TAG, e.getLocalizedMessage(), e);
                }

            }

            return result;
        }

        @Override
        protected void onPostExecute(String response) {

            handleResponse(response);
            pDlg.dismiss();

        }

        // Establish connection and socket (data retrieval) timeouts
        private HttpParams getHttpParams() {

            HttpParams htpp = new BasicHttpParams();

            HttpConnectionParams.setConnectionTimeout(htpp, CONN_TIMEOUT);
            HttpConnectionParams.setSoTimeout(htpp, SOCKET_TIMEOUT);

            return htpp;
        }

        private HttpResponse doResponse(String url) {

            // Use our connection and data timeouts as parameters for our
            // DefaultHttpClient
            HttpClient httpclient = new DefaultHttpClient(getHttpParams());

            HttpResponse response = null;

            try {
                switch (taskType) {

                    case POST_TASK:
                        HttpPost httppost = new HttpPost(url);
                        // Add parameters
                        httppost.setEntity(new UrlEncodedFormEntity(params));

                        response = httpclient.execute(httppost);
                        break;
                    case GET_TASK:
                        HttpGet httpget = new HttpGet(url);
                        response = httpclient.execute(httpget);
                        break;
                }
            } catch (Exception e) {

                Log.e(TAG, e.getLocalizedMessage(), e);

            }

            return response;
        }

        private String inputStreamToString(InputStream is) {

            String line = "";
            StringBuilder total = new StringBuilder();

            // Wrap a BufferedReader around the InputStream
            BufferedReader rd = new BufferedReader(new InputStreamReader(is));

            try {
                // Read response until the end
                while ((line = rd.readLine()) != null) {
                    total.append(line);
                }
            } catch (IOException e) {
                Log.e(TAG, e.getLocalizedMessage(), e);
            }

            // Return full string
            return total.toString();
        }

    }


}
