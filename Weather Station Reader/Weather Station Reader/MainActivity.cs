using System;
using Android.App;
using Android.Widget;
using Android.Content;
using Android.Provider;
using RestSharp;
using Android.Graphics;
using Android.OS;



namespace Weather_Station_Reader
{
    [Activity(Label = "Weather Station Reader", MainLauncher = true, Icon = "@drawable/icon")]
    public class MainActivity : Activity
    {
        protected override void OnCreate(Bundle bundle)
        {
            base.OnCreate(bundle);

            // Set our view from the "main" layout resource
            SetContentView (Resource.Layout.Main);
            Button getdata = FindViewById<Button>(Resource.Id.GetData);
            Button accessData = FindViewById<Button>(Resource.Id.button1);
            Button deleteData = FindViewById<Button>(Resource.Id.button2);

            getdata.Click += (object sender, EventArgs e) =>
            {
                var intent = new Intent(this, typeof(GetData));
                StartActivity(intent);

            };

            accessData.Click += (object sender, EventArgs e) =>
            {
                var intent = new Intent(this, typeof(AccessData));
                StartActivity(intent);
            };

            deleteData.Click += (object sender, EventArgs e) =>
            {
                var intent = new Intent(this, typeof(DeleteData));
                StartActivity(intent);
            };
        }


    }
}


