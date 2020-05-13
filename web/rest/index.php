<? require 'rest_cliente.php';
$TEST_SERVER_URL = "https://www.zonapagos.com/Apis_CicloPago/api/InicioPago";

$api = new RestClient;
$result = $api->post($TEST_SERVER_URL, "{  \"InformacionPago\": {    \"flt_total_con_iva\":60000,    \"flt_valor_iva\": 0,    \"str_id_pago\": \"199998\",    \"str_descripcion_pago\": \"Movil\",    \"str_email\": \"soporte9@zonavirtual.com\",    \"str_id_cliente\": \"123456789\",    \"str_tipo_id\": \"1\",    \"str_nombre_cliente\": \"Elsa\",    \"str_apellido_cliente\": \"Pineros\",    \"str_telefono_cliente\": \"319632555648\",    \"str_opcional1\": \"Bateria\",    \"str_opcional2\": \"Audifonos\",    \"str_opcional3\": \"Cargador corto\",    \"str_opcional4\": \"Cargador largo\",    \"str_opcional5\": \"Simcard\"  },  \"InformacionSeguridad\": {    \"int_id_comercio\":23361,    \"str_usuario\": \"SedCol\",    \"str_clave\": \"Colombia23361*\",    \"int_modalidad\": 1  },  \"AdicionalesPago\": [    {      \"int_codigo\": 111,      \"str_valor\": \"1\"    },    {      \"int_codigo\": 112,      \"str_valor\": \"0\"    }  ],\"AdicionalesConfiguracion\": [    {      \"int_codigo\": 50,      \"str_valor\": \"2701\"    },    {      \"int_codigo\": 100,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 101,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 102,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 103,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 104,      \"str_valor\": \"https://www.google.com.co/\"    },    {      \"int_codigo\": 105,      \"str_valor\": \"10000\"    },    {      \"int_codigo\": 106,      \"str_valor\": \"3\"    },    {      \"int_codigo\": 107,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 108,      \"str_valor\": \"1\"    },    {      \"int_codigo\": 109,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 110,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 113,      \"str_valor\": \"0\"    },    {      \"int_codigo\": 114,      \"str_valor\": \"96\"    },    {      \"int_codigo\": 115,      \"str_valor\": \"1\"    }  ]}",
array('Content-Type' => 'application/json'));

print_r($result);

//$response_json = $result->decode_response();



/*$this->assertEquals('application/json', 
$response_json->headers->{"Content-Type"});
$this->assertEquals('POST', 
$response_json->SERVER->REQUEST_METHOD);
$this->assertEquals("{\"foo\":\"bar\"}", 
$response_json->body);*/
?>