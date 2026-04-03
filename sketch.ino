#include <WiFi.h>
#include <HTTPClient.h>
#include <ESP32Servo.h>
#include "DHT.h"
#include <LiquidCrystal_I2C.h>

// 1. Configuración de Red y API
const char* ssid = "Wokwi-GUEST";
const char* password = "";
const char* serverName = "https://webhook.site/a1fef55b-e8c4-4a53-942d-1a3834dc0e9f"; 

// 2. Definición de Actuadores y Sensores
Servo servoVent;
Servo servoRiego;
const int pinServoVent = 18;
const int pinServoRiego = 19;

#define DHTPIN 15
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 16, 2);

void setup() {
  Serial.begin(115200);
  delay(2000); 
  Serial.println("\n--- BOLLOTECH V1.8 | OPTIMIZED RANGES ---");

  dht.begin();
  
  // Configuración de Servos
  servoVent.attach(pinServoVent);
  servoRiego.attach(pinServoRiego);
  servoVent.write(0); 
  servoRiego.write(0);

  // LCD Branding
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("FLORENTICA V1.8");
  lcd.setCursor(0, 1);
  lcd.print("Buscando WiFi...");

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nWiFi ONLINE");
  lcd.clear();
  lcd.print("RANGOS OPTIMOS");
  delay(1500);
}

void loop() {
  float h = dht.readHumidity();
  float t = dht.readTemperature();

  if (isnan(h) || isnan(t)) return;

  String estadoVent = "OFF ";
  String estadoRiego = "OFF ";

  // --- LÓGICA DE CONTROL CON RANGOS PROFESIONALES ---

// --- LÓGICA DE CONTROL ---
if (t >= 28.0) {
  servoVent.write(90); 
  estadoVent = "ON  ";  // <--- Agregué un espacio aquí
} else if (t <= 24.0) {
  servoVent.write(0);
  estadoVent = "OFF ";
}

if (h <= 45.0) {
  servoRiego.write(90); 
  estadoRiego = "ON "; // <--- Agregué un espacio aquí
} else if (h >= 60.0) {
  servoRiego.write(0);
  estadoRiego = "OFF ";
}

  // --- INTERFAZ LOCAL (LCD) ---
  lcd.setCursor(0, 0);
  lcd.print("T:"); lcd.print(t, 1); lcd.print("C V:"); lcd.print(estadoVent);
  lcd.setCursor(0, 1);
  lcd.print("H:"); lcd.print(h, 1); lcd.print("% R:"); lcd.print(estadoRiego);

  // --- TRANSMISIÓN A BOLLOTECH CLOUD ---
  if(WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    if (http.begin(serverName)) {
      http.addHeader("Content-Type", "application/json");

      String jsonPayload = "{\"id\":\"FL_01\",\"temp\":" + String(t) + 
                           ",\"hum\":" + String(h) + ",\"v\":\"" + estadoVent + 
                           "\",\"r\":\"" + estadoRiego + "\"}";
      
      int code = http.POST(jsonPayload);
      Serial.print("Data: "); Serial.print(jsonPayload);
      Serial.print(" | Status: "); Serial.println(code);
      http.end();
    }
  }

  delay(5000); 
}