# -*- coding: utf-8 -*-

# import the necessary modules to ineract with MQTT and MySQL, convert JSON into a Python dictionary
import paho.mqtt.client as mqtt
import mysql.connector
from json import loads
from config import *
from sys import exit


# Connection to the database
db = mysql.connector.connect(
    host=MYSQL_HOST,
    user=MYSQL_USER,
    password=MYSQL_PASS,
    database=MYSQL_DB
)
cursor = db.cursor()

def on_connect(client, userdata, flags, rc):
    """ Function executed when the client receives a connection response from the MQTT server """
    print('Connexion au serveur MQTT')
    # Subscribe to the right topic defined in the config file
    client.subscribe(MQTT_TOPIC)


def on_message(client, userdata, msg):
    """ Function executed when a PUBLISH message is received from the server """
    # Decode the payload in UTF-8
    data = msg.payload.decode('utf-8')
    print("\n > Valeurs pour le batiment " + msg.topic[16:-5])

    # msg.topic[16:-5] grabs the room from the topic of the published message
    # the room must be within the targeted rooms inside the MQTT_ROOMS list
    if msg.topic[16:-5] in MQTT_ROOMS:
        
        print("\n > Valeurs pour le batiment " + msg.topic[16:-5])

        # convert JSON into a Python object (JSON is within an array, hence the [0])
        payload = loads(data)[0]

        # for each sensor, retrieve the associated data within the payload and send it to the database
        for sensor in MQTT_SENSORS:

            data = payload[sensor]

            # retrieve the sensor id
            # msg.topic[16:-5] is the room
            # NOM_CAPT[sensor] is a dictionary used to translate the type of the sensor into French
            # the name of the sensor in the SQL table is "[ROOM]-[SENSOR_TYPE_IN_FRENCH]" (e.g. "E208-température")
            sensor_name = msg.topic[16:-5] + '-' + NOM_CAPT[sensor]
            cursor.execute("SELECT id_capt FROM Capteur WHERE nom_capt = '" + sensor_name + "'")
            # fetchone() returns '(id_bat,)' so fetchone()[0] returns 'id_bat'
            sensor_id = cursor.fetchone()[0]

            print(" - Insertion de la valeur " + str(data) + " (" + sensor + ") dans la table")

            # insert the data into the Mesure table
            insert_data = "INSERT INTO `Mesure`(`id_capt`, `date_mes`, `horaire_mes`, `valeur_mes`) VALUES (%s, curdate(), curtime(), %s)"
            values = (sensor_id, data)
            cursor.execute(insert_data, values)

            # commit the queries to make the change inside the table
            db.commit()
            


# instanciate the mqtt client and link the above-defined functions with the correct events of the client
mqtt_client = mqtt.Client()
mqtt_client.on_connect = on_connect
mqtt_client.on_message = on_message

# connect to the MQTT server and loop forever
mqtt_client.connect(MQTT_ADDRESS, MQTT_PORT)
mqtt_client.loop_forever()