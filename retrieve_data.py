import paho.mqtt.client as mqtt
import mysql.connector
from datetime import datetime
from config import *
from random import randing

db = mysql.connector.connect(
  host=MYSQL_HOST,
  user=MYSQL_USER,
  password=MYSQL_PASS,
  database=MYSQ_DB
)


def on_connect(client, userdata, flags, rc):
    """ The callback for when the client receives a connection response from the server. """
    print('Connexion au serveur MQTT')
    client.subscribe(MQTT_TOPIC)


def on_message(client, userdata, msg):
    """ The callback for when a PUBLISH message is received from the server. """
    data = msg.payload.decode()
    print(msg.topic + ' : ' + data)
    parse_message(msg.topic, data)


def parse_message(topic, data):
    """ This callback parses input and sends them to the database """
    """
    cursor = db.cursor()
    now = datetime.now()
    today = now.strftime("%d/%m/%Y")
    hour = now.strftime("%H:%M:%S")

    query = "INSERT INTO `Mesure`(`id_capt`, `date_mes`, `horarire_mes`, `valeur_mes`) VALUES (%s, %s, %s, %s, %s)"
    val = (topic, today, hour, data)
    # changer le 'topic' pour la colonne id_capt

    cursor.execute(sql, val)

    db.commit()
    """
    pass


mqtt_client = mqtt.Client()
mqtt_client.username_pw_set(MQTT_USER, MQTT_PASSWORD)
mqtt_client.on_connect = on_connect
mqtt_client.on_message = on_message

mqtt_client.connect(MQTT_ADDRESS, MQTT_PORT)
mqtt_client.loop_forever()