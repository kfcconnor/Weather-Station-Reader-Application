using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using RestSharp;
using Newtonsoft;
using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;



namespace Weather_Station_Reader
{
    [Activity(Label = "Get data from server")]
    public class GetData : Activity
    {
        public class sensorData
        {
            public string time_stamp { get; set; }
            public string sensor_id { get; set; }
            public string sensor_type { get; set; }
            public string value { get; set; }
        }
        
        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            SetContentView(Resource.Layout.GetData);
            // Create your application here
            Button search = FindViewById<Button>(Resource.Id.button1);
            EditText Timestamp = FindViewById<EditText>(Resource.Id.editText1);
            EditText sensorId = FindViewById<EditText>(Resource.Id.editText2);
            EditText sensorType = FindViewById<EditText>(Resource.Id.editText3);
            EditText value = FindViewById<EditText>(Resource.Id.editText4);

            search.Click += (object sender, EventArgs e) =>
            {
                
                var intent = new Intent(this, typeof(Data_Return));
                intent.PutExtra("Timestamp", Timestamp.Text);
                intent.PutExtra("Sensor_id", sensorId.Text);
                intent.PutExtra("Sensor_type", sensorType.Text);
                intent.PutExtra("Value", value.Text);
                // start the activity and pass the parameter to testActivity
                StartActivity(intent);

            };

        }
    }
    

    

    
}