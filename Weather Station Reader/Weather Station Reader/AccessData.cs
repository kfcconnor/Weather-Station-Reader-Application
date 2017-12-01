using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using RestSharp;
using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;

namespace Weather_Station_Reader
{
    [Activity(Label = "AccessData")]
    public class AccessData : Activity
    {
        public class accessData
        {
            public string time_stamp { get; set; }
            public string longitude { get; set; }
            public string latitude { get; set; }
        }
        public List<accessData> dataList = new List<accessData>();
        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            SetContentView(Resource.Layout.accessDataOutput);
            // Create your application here
            string url = string.Format("http://connorthompsonwebapp.azurewebsites.net/index.php/access/getaccessdata");
            // Create your application here
            var client = new RestClient(url);
            var request = new RestRequest(Method.GET);
            request.AddHeader("postman-token", "35a29e73-6d99-edee-e6b7-2465b1b98d4d");
            request.AddHeader("cache-control", "no-cache");
            IRestResponse response = client.Execute(request);
            RestSharp.Deserializers.JsonDeserializer deserial = new RestSharp.Deserializers.JsonDeserializer();
            dataList = deserial.Deserialize<List<accessData>>(response);

            ListView getdata = FindViewById<ListView>(Resource.Id.listView1);
            List<string> DataItems = new List<string>();
            foreach (accessData data in dataList) {
                DataItems.Add(string.Format("Time: {0} |Longitude: {1} |Latitude: {2} ", data.time_stamp, data.longitude, data.latitude));
            }
            ArrayAdapter<string> adapter = new ArrayAdapter<string>(this, Android.Resource.Layout.SimpleListItem1, DataItems);

            getdata.Adapter = adapter;
        }
    }
}