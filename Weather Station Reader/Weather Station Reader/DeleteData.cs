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
    [Activity(Label = "DeleteData")]
    public class DeleteData : Activity
    {
        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            SetContentView(Resource.Layout.DeleteData);
            // Create your application here
            Button delete = FindViewById<Button>(Resource.Id.button1);           
            EditText sensorId = FindViewById<EditText>(Resource.Id.editText2);
            EditText sensorType = FindViewById<EditText>(Resource.Id.editText3);
            EditText value = FindViewById<EditText>(Resource.Id.editText4);

            delete.Click += (object sender, EventArgs e) =>
            {
                string url = string.Format("http://connorthompsonwebapp.azurewebsites.net/index.php/sensors/deletesensordata?sensorid={0}&sensortype={1}&value={2}", sensorId.Text, sensorType.Text, value.Text);
                var client = new RestClient(url);
                var request = new RestRequest(Method.DELETE);
                request.AddHeader("postman-token", "34776c22-ff23-332b-e982-6deae6cf81f4");
                request.AddHeader("cache-control", "no-cache");
                IRestResponse response = client.Execute(request);
            };
        }
    }
}