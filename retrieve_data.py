# -*- coding: utf-8 -*-
"""
	retrieve_data.py

    Program which retrieves all the data through MQTT and insert them in the database
    Rely on the configuration file config.py

    Don't forget to install the required modules with pip:
    $ pip install paho-mqtt
    $ pip install mysql-connector

    Make this program a cron job by inserting the following line into your crontab file (crontab -e)
    */10  * * * * python3 /path/to/the/program/retrieve_data.py
"""

# import the necessary modules to ineract with MQTT and MySQL, convert JSON into a Python dictionary
import paho.mqtt.client as mqtt
import mysql.connector
from json import loads
from config import *

# the program ends once all the sensors data has been retrieved, thus we need to create a variable which holds the number of sensors retrieved
ROOMS_RETRIEVED = 0
ROOMS_TO_RETRIEVE = len(MQTT_ROOMS)

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
    # we make this variable global to be able to modify it within this function
    global ROOMS_RETRIEVED


    # msg.topic[16:-5] grabs the room from the topic of the published message
    # the room must be within the targeted rooms inside the MQTT_ROOMS list
    if msg.topic[16:-5] in MQTT_ROOMS:
        
        print("\n > Valeurs récupérées pour le batiment " + msg.topic[16:-5])
        
        # Decode the payload in UTF-8
        jsondecoded = msg.payload.decode()
        
        # convert JSON into a Python object (JSON is within an array, hence the [0])
        payload = loads(jsondecoded)[0]

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

            print(" - Insertion de la valeur " + str(data) + " (" + NOM_CAPT[sensor] + ") dans la table Mesure")

            # insert the data into the Mesure table
            insert_data = "INSERT INTO `Mesure`(`id_capt`, `date_mes`, `horaire_mes`, `valeur_mes`) VALUES (%s, curdate(), curtime(), %s)"
            values = (sensor_id, data)
            cursor.execute(insert_data, values)

            # commit the queries to make the change inside the table
            db.commit()
        
        # increment the value of ROOMS_RETRIEVED to track the number of rooms retrieved
        ROOMS_RETRIEVED += 1

        # if all the rooms have been retrieved, the program ends
        if ROOMS_RETRIEVED == ROOMS_TO_RETRIEVE:
            exit('\n\n Fin. \n\n')
            

# instanciate the mqtt client and link the above-defined functions with the correct events of the client
mqtt_client = mqtt.Client()
mqtt_client.on_connect = on_connect
mqtt_client.on_message = on_message

# connect to the MQTT server and loop forever
mqtt_client.connect(MQTT_ADDRESS, MQTT_PORT)
mqtt_client.loop_forever()
