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
using Android.Locations;
using Android.Util;

namespace Weather_Station_Reader
{
    [Activity(Label = "Data Return")]
    public class Data_Return : Activity
    {
        // create new instance of the Android Location Manager
        LocationManager locMgr;
        // select the GPS provider for location data
        string Provider = LocationManager.GpsProvider;
        // variables to store latitude and longitude of last known location
        double lat;
        double longi;
        public class sensorData
        {
            public string time_stamp { get; set; }
            public string sensor_id { get; set; }
            public string sensor_type { get; set; }
            public string value { get; set; }
        }
        public List<sensorData> dataList = new List<sensorData>();
        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            SetContentView(Resource.Layout.dataOutput);
            //logLocation();

            var timestamp = Intent.GetStringExtra("Timestamp");
            var sensorid = Intent.GetStringExtra("Sensor_id");
            var sensortype = Intent.GetStringExtra("Sensor_type");
            var value = Intent.GetStringExtra("Value");
            string url = string.Format("http://connorthompsonwebapp.azurewebsites.net/index.php/sensors/getsensordata?timestamp={0}&sensorid={1}&sensortype={2}&value={3}", timestamp, sensorid, sensortype, value);
            // Create your application here
            var client = new RestClient(url);
            var request = new RestRequest(Method.GET);
            request.AddHeader("postman-token", "35a29e73-6d99-edee-e6b7-2465b1b98d4d");
            request.AddHeader("cache-control", "no-cache");
            IRestResponse response = client.Execute(request);
            RestSharp.Deserializers.JsonDeserializer deserial = new RestSharp.Deserializers.JsonDeserializer();
            dataList = deserial.Deserialize<List<sensorData>>(response);

            ListView getdata = FindViewById<ListView>(Resource.Id.listView1);
            List<string> DataItems = new List<string>();
            foreach (sensorData data in dataList) {
                DataItems.Add(string.Format("Time: {0} ,ID: {1},Type: {2},Value {3}", data.time_stamp, data.sensor_id, data.sensor_type, data.value));
            }
            ArrayAdapter<string> adapter = new ArrayAdapter<string>(this, Android.Resource.Layout.SimpleListItem1, DataItems);

            getdata.Adapter = adapter;
        }
        public void logLocation()
        {
            // create new instance of the location manager
            locMgr = GetSystemService(Context.LocationService) as LocationManager;
            // check if GPS location provider is available
            if (locMgr.IsProviderEnabled(Provider)) {
                // get the last known location from the location manager
                Location lastKnownLocation = locMgr.GetLastKnownLocation(Provider);
                // bind the lat/long coordinates of last known location t0 double

                lat = lastKnownLocation.Latitude;
                longi = lastKnownLocation.Longitude;
            } else {
                Log.Info("Location error, ", Provider + " is not available. Does the device have location services enabled ? ");
            }
            string url = string.Format("http://connorthompsonwebapp.azurewebsites.net/index.php/access/postaccessdata?longitude={0}&latitude={1}", longi, lat);
            var client = new RestClient(url);
            var request = new RestRequest(Method.POST);
            request.AddHeader("postman-token", "b39f961f-2074-8ca3-62ea-caeb9c4dcdcf");
            request.AddHeader("cache-control", "no-cache");
            IRestResponse response = client.Execute(request);
        }
    }
}