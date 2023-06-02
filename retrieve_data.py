import paho.mqtt.client as mqtt
import mysql.connector
from json import loads
from datetime import datetime
from config import *

db = mysql.connector.connect(
    host=MYSQL_HOST,
    user=MYSQL_USER,
    password=MYSQL_PASS,
    database=MYSQL_DB
)


def on_connect(client, userdata, flags, rc):
    """ The callback for when the client receives a connection response from the server. """
    print('Connexion au serveur MQTT')
    client.subscribe(MQTT_TOPIC)


def on_message(client, userdata, msg):
    """ The callback for when a PUBLISH message is received from the server. """
    data = msg.payload.decode()
    print(msg.topic + ' : ' + data)

    # si la salle est parmi celles souhaitées, alors on insère les valeurs dans la base de données
    if msg.topic[16:-5] in MQTT_ROOMS:
        
        # conversion du JSON en un objet Python
        payload = loads(data)[0]
        
        cursor = db.cursor()
        now = datetime.now()
        today = now.strftime("%d/%m/%Y")
        hour = now.strftime("%H:%M:%S")

        for sensor in MQTT_SENSORS:

            #query = "INSERT INTO `Mesure`(`id_capt`, `date_mes`, `horaire_mes`, `valeur_mes`) VALUES (%s, %s, %s, %s)"
            #val = (payload[sensor], today, hour, data)
            #cursor.execute(sql, val)
            #db.commit()
        


mqtt_client = mqtt.Client()
mqtt_client.on_connect = on_connect
mqtt_client.on_message = on_message

mqtt_client.connect(MQTT_ADDRESS, MQTT_PORT)
mqtt_client.loop_forever()