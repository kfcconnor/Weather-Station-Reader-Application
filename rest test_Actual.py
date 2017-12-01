from twython import Twython
import requests
import Adafruit_DHT
import json
import threading

url = "http://connorthompsonwebapp.azurewebsites.net/index.php/sensors/postsensordata"
c = 0
APP_KEY = 'L06F7BEpJKd2IvcjQuSODRUkr'
APP_SECRET = 'vE9ILxm2aKz1XeqCG67pptmKVOylL02DbpBbui65cRgbQfId1d'
ACCESS_TOKEN = '261422432-G9MoOnOB86qXBlwQKHa2bBUop0LPyShpR9lAGDK4'
ACCESS_TOKEN_SECRET = 'ugCEjCh2E7yGILVtt4ulwXIFPIqd7lGTdKC4i7L8vG7K0'

twitter = Twython(APP_KEY, APP_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET)

def loop():
    global c
##    c += 1
    humidity, temperature = Adafruit_DHT.read_retry(11,4)
    querystring = {"sensorid":"1","sensortype":"temp","value":"{0:0.1f}".format(temperature)}
    querystring2 = {"sensorid":"2","sensortype":"humidity","value":"{0:0.1f}".format(humidity)}
    headers = {
        'cache-control': "no-cache",
        'postman-token': "cc329526-1ee9-9214-8440-eea8ffddd15b"
        }
##    //twitter.update_status(status = ('Beep Boop I am a Temp bot. Temp={0:0.1f}*C  Humidity={1:0.1f}% PostNo={2}'.format(temperature, humidity, c)))
    response = requests.request("POST", url, headers=headers, params=querystring)
    response2 = requests.request("POST", url, headers=headers, params=querystring2)
    print(response.text)
    print(response2.text)
    threading.Timer(300, loop).start()


loop()
##myResponse = requests.post(url)
##print (myResponse.status_code)
##print (myResponse.text)
