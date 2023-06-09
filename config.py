# -*- coding: utf-8 -*-
"""
	config.py
	Configuration file containing constant variables used in retrieve_data.py
	-> MQTT and MySQL information
"""

MQTT_ADDRESS = 'mqtt.iut-blagnac.fr'
MQTT_PORT = 1883
MQTT_TOPIC = 'Student/by-room/+/data'
MQTT_ROOMS = ['E208', 'B106']
MQTT_SENSORS = ['temperature', 'illumination']
NOM_CAPT = {
	'illumination': 'luminosité',
	'temperature':  'température'
}
MYSQL_HOST = 'localhost'
MYSQL_DB = 'sae23'
MYSQL_USER = 'john'
MYSQL_PASS = 'john123'