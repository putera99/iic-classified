/*----gz 1  sh 3  bj 2  sz 4*/

UPDATE `iic_arctype` SET `reid` = '1001' WHERE `id` in (1025,1090,1091,1092,1093,1153,1283);

UPDATE `iic_archives` SET `cid` = '1' WHERE `typeid` in (1025,1090,1091,1092,1093);



/*-----------------------Arts&Culture->Shanghai  -->Arts&Culture*/
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1025' WHERE `typeid` in (1149);

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1090' WHERE `typeid` in (1150);

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1091' WHERE `typeid` in (1151);

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1092' WHERE `typeid` in (1152);

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1153' WHERE `typeid` in (1153);

/*-------------Arts&Culture->Beijing*/

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1025' WHERE `typeid` in (1236);

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1090' WHERE `typeid` in (1237);

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1091' WHERE `typeid` in (1238);

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1092' WHERE `typeid` in (1239);

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1153' WHERE `typeid` in (1240);

/*-------------Arts&Culture->Shenzhen*/

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1025' WHERE `typeid` in (1279);

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1090' WHERE `typeid` in (1280);

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1091' WHERE `typeid` in (1281);

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1092' WHERE `typeid` in (1282);

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1283' WHERE `typeid` in (1283);

/*----Bars gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_archives` SET `cid` = '1',`typeid` = '1002' WHERE `typeid`=(1029);

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1002' WHERE `typeid`=(1167);

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1002' WHERE `typeid`=(1241);

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1002' WHERE `typeid`=(1284);


/*---------Education*/

UPDATE `iic_arctype` SET `reid` = '1006' WHERE `id` in (1094,1095,1098,1332);

UPDATE `iic_archives` SET `cid` = '1' WHERE `typeid` in (1094,1095,1098);

/*--------Education sh*/
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1094' WHERE `typeid`=1330;

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1095' WHERE `typeid`=1331;

UPDATE `iic_archives` SET `cid` = '3' WHERE `typeid`=1332;

/*--------Education bj*/
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1094' WHERE `typeid`=1243;

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1095' WHERE `typeid`=1244;

/*--------Education sz*/
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1094' WHERE `typeid`=1287;

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1095' WHERE `typeid`=1288;

UPDATE `iic_archives` SET `cid` = '4',`typeid`=1332 WHERE `typeid`=1289;

/*------------------------------- Hotel*/
UPDATE `iic_arctype` SET `reid` = '1007' WHERE `id` in (1099,1100);

UPDATE `iic_archives` SET `cid` = '1' WHERE `typeid` in (1099,1100);

/*--------sh*/
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1099' WHERE `typeid`=1171;

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1100' WHERE `typeid`=1172;

/*--------bj*/
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1099' WHERE `typeid`=1256;

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1100' WHERE `typeid`=1257;
/*----------sz*/
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1099' WHERE `typeid`=1291;

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1100' WHERE `typeid`=1292;

/*-------- 	Leisure*/
UPDATE `iic_arctype` SET `reid` = '1008' WHERE `id` in (1111,1212,1213);
UPDATE `iic_archives` SET `cid` = '1' WHERE `typeid` in (1111,1212,1213);

/*----sh*/
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1111' WHERE `typeid`=1209;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1212' WHERE `typeid`=1210;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1213' WHERE `typeid`=1211;

/*------bj*/

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1111' WHERE `typeid`=1246;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1212' WHERE `typeid`=1274;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1213' WHERE `typeid`=1275;

/*-----------sz*/
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1111' WHERE `typeid`=1294;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1212' WHERE `typeid`=1295;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1213' WHERE `typeid`=1296;

/*--------------- 	Restaurant*/
UPDATE `iic_arctype` SET `reid` = '1010' WHERE `id` in (1113,1114,1115,1117,1118,1119,1120,1121,1122,1126,1123,1124,1125,1125,1127,1128,1129,1194,1195,1197,1270,1271,1272,1314,1310,1311);
UPDATE `iic_archives` SET `cid` = '1' WHERE `typeid` in (1113,1114,1115,1117,1118,1119,1120,1121,1122,1126,1123,1124,1125,1125,1127,1128,1129);

/*--------sh*/
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1113' WHERE `typeid`=1174;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1114' WHERE `typeid`=1175;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1117' WHERE `typeid`=1178;

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1119' WHERE `typeid`=1179;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1120' WHERE `typeid`=1180;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1121' WHERE `typeid`=1181;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1122' WHERE `typeid`=1182;

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1123' WHERE `typeid`=1183;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1126' WHERE `typeid`=1186;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1127' WHERE `typeid`=1187;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1128' WHERE `typeid`=1188;

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1129' WHERE `typeid`=1189;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1194' WHERE `typeid`=1194;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1195' WHERE `typeid`=1195;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1197' WHERE `typeid`=1197;

/*--------bj*/
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1113' WHERE `typeid`=1259;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1114' WHERE `typeid`=1260;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1115' WHERE `typeid`=1261;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1119' WHERE `typeid`=1262;

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1120' WHERE `typeid`=1263;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1121' WHERE `typeid`=1264;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1122' WHERE `typeid`=1265;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1125' WHERE `typeid`=1266;

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1126' WHERE `typeid`=1267;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1127' WHERE `typeid`=1268;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1128' WHERE `typeid`=1269;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1270' WHERE `typeid`=1270;

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1271' WHERE `typeid`=1271;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1272' WHERE `typeid`=1272;

/*-----sz*/
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1113' WHERE `typeid`=1298;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1114' WHERE `typeid`=1299;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1121' WHERE `typeid`=1301;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1195' WHERE `typeid`=1302;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1126' WHERE `typeid`=1303;

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1120' WHERE `typeid`=1304;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1270' WHERE `typeid`=1305;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1125' WHERE `typeid`=1306;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1113' WHERE `typeid`=1307;

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1113' WHERE `typeid`=1308;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1117' WHERE `typeid`=1309;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1310' WHERE `typeid`=1310;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1311' WHERE `typeid`=1311;

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1122' WHERE `typeid`=1312;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1271' WHERE `typeid`=1313;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1314' WHERE `typeid`=1314;


/*--------- Shopping gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_arctype` SET `reid` = '1011' WHERE `id` in (1102,1103,1105,1106,1327,1158,1248,1251,1252,1323);
UPDATE `iic_archives` SET `cid` = '1' WHERE `typeid` in (1102,1103,1105,1106,1327);

/*----sh*/
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1106' WHERE `typeid`=1156;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1158' WHERE `typeid`=1158;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1102' WHERE `typeid`=1160;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1105' WHERE `typeid`=1161;

/*-------- bj*/
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1248' WHERE `typeid`=1248;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1105' WHERE `typeid`=1249;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1106' WHERE `typeid`=1250;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1251' WHERE `typeid`=1251;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1252' WHERE `typeid`=1252;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1327' WHERE `typeid`=1328;

/*------sz*/
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1248' WHERE `typeid`=1321;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1105' WHERE `typeid`=1322;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1323' WHERE `typeid`=1323;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1106' WHERE `typeid`=1324;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1251' WHERE `typeid`=1325;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1327' WHERE `typeid`=1329;


/*--------- Consulates gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_archives` SET `cid` = '1',`typeid`=1108 WHERE `typeid`=1109;
UPDATE `iic_archives` SET `cid` = '3',`typeid`=1108 WHERE `typeid`=1154;
UPDATE `iic_archives` SET `cid` = '2',`typeid`=1108 WHERE `typeid`=1253;

/*--------- Health gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_arctype` SET `reid` = '1133' WHERE `id` in (1143,1144);
UPDATE `iic_archives` SET `cid` = '1' WHERE `typeid` in (1143,1144);

UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1143' WHERE `typeid`=1192;
UPDATE `iic_archives` SET `cid` = '3',`typeid` = '1144' WHERE `typeid`=1193;

UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1143' WHERE `typeid`=1300;
UPDATE `iic_archives` SET `cid` = '2',`typeid` = '1144' WHERE `typeid`=1276;

UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1143' WHERE `typeid`=1318;
UPDATE `iic_archives` SET `cid` = '4',`typeid` = '1144' WHERE `typeid`=1317;

/*--------- Banking gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_archives` SET `cid` = '1',`typeid`=1135 WHERE `typeid`=1136;
UPDATE `iic_archives` SET `cid` = '3',`typeid`=1135 WHERE `typeid`=1190;
UPDATE `iic_archives` SET `cid` = '2',`typeid`=1135 WHERE `typeid`=1277;
UPDATE `iic_archives` SET `cid` = '4',`typeid`=1135 WHERE `typeid`=1319;

/*--------- Cafes gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_archives` SET `cid` = '1',`typeid`=1145 WHERE `typeid`=1146;
UPDATE `iic_archives` SET `cid` = '3',`typeid`=1145 WHERE `typeid`=1170;
UPDATE `iic_archives` SET `cid` = '2',`typeid`=1145 WHERE `typeid`=1273;
UPDATE `iic_archives` SET `cid` = '4',`typeid`=1145 WHERE `typeid`=1285;

/*---------  	City Events gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_archives` SET `cid` = '1',`typeid`=1226 WHERE `typeid`=1229;
UPDATE `iic_archives` SET `cid` = '3',`typeid`=1226 WHERE `typeid`=1228;
UPDATE `iic_archives` SET `cid` = '2',`typeid`=1226 WHERE `typeid`=1227;
UPDATE `iic_archives` SET `cid` = '4',`typeid`=1226 WHERE `typeid`=1230;

/*---------  	Agencies gz 1  sh 3  bj 2  sz 4*/
UPDATE `iic_archives` SET `cid` = '1',`typeid`=1333 WHERE `typeid`=1336;
UPDATE `iic_archives` SET `cid` = '3',`typeid`=1333 WHERE `typeid`=1335;
UPDATE `iic_archives` SET `cid` = '2',`typeid`=1333 WHERE `typeid`=1334;
UPDATE `iic_archives` SET `cid` = '4',`typeid`=1333 WHERE `typeid`=1337;







DELETE FROM `iic_arctype` WHERE `id` = 1149;
DELETE FROM `iic_arctype` WHERE `id` = 1150;
DELETE FROM `iic_arctype` WHERE `id` = 1151;
DELETE FROM `iic_arctype` WHERE `id` = 1152;
DELETE FROM `iic_arctype` WHERE `id` = 1148;
DELETE FROM `iic_arctype` WHERE `id` = 1236;
DELETE FROM `iic_arctype` WHERE `id` = 1237;
DELETE FROM `iic_arctype` WHERE `id` = 1238;
DELETE FROM `iic_arctype` WHERE `id` = 1239;
DELETE FROM `iic_arctype` WHERE `id` = 1240;
DELETE FROM `iic_arctype` WHERE `id` = 1235;
DELETE FROM `iic_arctype` WHERE `id` = 1279;
DELETE FROM `iic_arctype` WHERE `id` = 1280;
DELETE FROM `iic_arctype` WHERE `id` = 1281;
DELETE FROM `iic_arctype` WHERE `id` = 1282;
DELETE FROM `iic_arctype` WHERE `id` = 1278;
DELETE FROM `iic_arctype` WHERE `id` = 1029;
DELETE FROM `iic_arctype` WHERE `id` = 1167;
DELETE FROM `iic_arctype` WHERE `id` = 1241;
DELETE FROM `iic_arctype` WHERE `id` = 1284;
DELETE FROM `iic_arctype` WHERE `id` = 1037;
DELETE FROM `iic_arctype` WHERE `id` = 1330;
DELETE FROM `iic_arctype` WHERE `id` = 1331;
DELETE FROM `iic_arctype` WHERE `id` = 1157;
DELETE FROM `iic_arctype` WHERE `id` = 1243;
DELETE FROM `iic_arctype` WHERE `id` = 1244;
DELETE FROM `iic_arctype` WHERE `id` = 1242;
DELETE FROM `iic_arctype` WHERE `id` = 1287;
DELETE FROM `iic_arctype` WHERE `id` = 1288;
DELETE FROM `iic_arctype` WHERE `id` = 1289;
DELETE FROM `iic_arctype` WHERE `id` = 1286;
DELETE FROM `iic_arctype` WHERE `id` = 1042;
DELETE FROM `iic_arctype` WHERE `id` = 1171;
DELETE FROM `iic_arctype` WHERE `id` = 1172;
DELETE FROM `iic_arctype` WHERE `id` = 1169;
DELETE FROM `iic_arctype` WHERE `id` = 1256;
DELETE FROM `iic_arctype` WHERE `id` = 1257;
DELETE FROM `iic_arctype` WHERE `id` = 1255;
DELETE FROM `iic_arctype` WHERE `id` = 1291;
DELETE FROM `iic_arctype` WHERE `id` = 1292;
DELETE FROM `iic_arctype` WHERE `id` = 1290;
DELETE FROM `iic_arctype` WHERE `id` = 1046;
DELETE FROM `iic_arctype` WHERE `id` = 1209;
DELETE FROM `iic_arctype` WHERE `id` = 1210;
DELETE FROM `iic_arctype` WHERE `id` = 1211;
DELETE FROM `iic_arctype` WHERE `id` = 1159;
DELETE FROM `iic_arctype` WHERE `id` = 1246;
DELETE FROM `iic_arctype` WHERE `id` = 1274;
DELETE FROM `iic_arctype` WHERE `id` = 1275;
DELETE FROM `iic_arctype` WHERE `id` = 1245;
DELETE FROM `iic_arctype` WHERE `id` = 1294;
DELETE FROM `iic_arctype` WHERE `id` = 1295;
DELETE FROM `iic_arctype` WHERE `id` = 1296;
DELETE FROM `iic_arctype` WHERE `id` = 1293;
DELETE FROM `iic_arctype` WHERE `id` = 1066;
DELETE FROM `iic_arctype` WHERE `id` = 1156;
DELETE FROM `iic_arctype` WHERE `id` = 1160;
DELETE FROM `iic_arctype` WHERE `id` = 1161;
DELETE FROM `iic_arctype` WHERE `id` = 1155;
DELETE FROM `iic_arctype` WHERE `id` = 1249;
DELETE FROM `iic_arctype` WHERE `id` = 1250;
DELETE FROM `iic_arctype` WHERE `id` = 1328;
DELETE FROM `iic_arctype` WHERE `id` = 1247;
DELETE FROM `iic_arctype` WHERE `id` = 1321;
DELETE FROM `iic_arctype` WHERE `id` = 1322;
DELETE FROM `iic_arctype` WHERE `id` = 1324;
DELETE FROM `iic_arctype` WHERE `id` = 1325;
DELETE FROM `iic_arctype` WHERE `id` = 1329;
DELETE FROM `iic_arctype` WHERE `id` = 1320;
DELETE FROM `iic_arctype` WHERE `id` = 1109;
DELETE FROM `iic_arctype` WHERE `id` = 1154;
DELETE FROM `iic_arctype` WHERE `id` = 1253;
DELETE FROM `iic_arctype` WHERE `id` = 1134;
DELETE FROM `iic_arctype` WHERE `id` = 1192;
DELETE FROM `iic_arctype` WHERE `id` = 1193;
DELETE FROM `iic_arctype` WHERE `id` = 1191;
DELETE FROM `iic_arctype` WHERE `id` = 1276;
DELETE FROM `iic_arctype` WHERE `id` = 1300;
DELETE FROM `iic_arctype` WHERE `id` = 1254;
DELETE FROM `iic_arctype` WHERE `id` = 1317;
DELETE FROM `iic_arctype` WHERE `id` = 1318;
DELETE FROM `iic_arctype` WHERE `id` = 1316;
DELETE FROM `iic_arctype` WHERE `id` = 1136;
DELETE FROM `iic_arctype` WHERE `id` = 1190;
DELETE FROM `iic_arctype` WHERE `id` = 1277;
DELETE FROM `iic_arctype` WHERE `id` = 1319;
DELETE FROM `iic_arctype` WHERE `id` = 1146;
DELETE FROM `iic_arctype` WHERE `id` = 1170;
DELETE FROM `iic_arctype` WHERE `id` = 1273;
DELETE FROM `iic_arctype` WHERE `id` = 1285;
DELETE FROM `iic_arctype` WHERE `id` = 1227;
DELETE FROM `iic_arctype` WHERE `id` = 1228;
DELETE FROM `iic_arctype` WHERE `id` = 1229;
DELETE FROM `iic_arctype` WHERE `id` = 1230;
DELETE FROM `iic_arctype` WHERE `id` = 1334;
DELETE FROM `iic_arctype` WHERE `id` = 1335;
DELETE FROM `iic_arctype` WHERE `id` = 1336;
DELETE FROM `iic_arctype` WHERE `id` = 1337;

DELETE FROM `iic_arctype` WHERE `id` = 1051;
DELETE FROM `iic_arctype` WHERE `id` = 1174;
DELETE FROM `iic_arctype` WHERE `id` = 1175;
DELETE FROM `iic_arctype` WHERE `id` = 1178;
DELETE FROM `iic_arctype` WHERE `id` = 1179;
DELETE FROM `iic_arctype` WHERE `id` = 1180;
DELETE FROM `iic_arctype` WHERE `id` = 1181;
DELETE FROM `iic_arctype` WHERE `id` = 1182;
DELETE FROM `iic_arctype` WHERE `id` = 1183;
DELETE FROM `iic_arctype` WHERE `id` = 1186;
DELETE FROM `iic_arctype` WHERE `id` = 1187;
DELETE FROM `iic_arctype` WHERE `id` = 1188;
DELETE FROM `iic_arctype` WHERE `id` = 1189;
DELETE FROM `iic_arctype` WHERE `id` = 1173;
DELETE FROM `iic_arctype` WHERE `id` = 1259;
DELETE FROM `iic_arctype` WHERE `id` = 1260;
DELETE FROM `iic_arctype` WHERE `id` = 1261;
DELETE FROM `iic_arctype` WHERE `id` = 1262;
DELETE FROM `iic_arctype` WHERE `id` = 1263;
DELETE FROM `iic_arctype` WHERE `id` = 1264;
DELETE FROM `iic_arctype` WHERE `id` = 1265;
DELETE FROM `iic_arctype` WHERE `id` = 1266;
DELETE FROM `iic_arctype` WHERE `id` = 1267;
DELETE FROM `iic_arctype` WHERE `id` = 1268;
DELETE FROM `iic_arctype` WHERE `id` = 1269;
DELETE FROM `iic_arctype` WHERE `id` = 1258;
DELETE FROM `iic_arctype` WHERE `id` = 1298;
DELETE FROM `iic_arctype` WHERE `id` = 1299;
DELETE FROM `iic_arctype` WHERE `id` = 1301;
DELETE FROM `iic_arctype` WHERE `id` = 1302;
DELETE FROM `iic_arctype` WHERE `id` = 1303;
DELETE FROM `iic_arctype` WHERE `id` = 1304;
DELETE FROM `iic_arctype` WHERE `id` = 1305;
DELETE FROM `iic_arctype` WHERE `id` = 1306;
DELETE FROM `iic_arctype` WHERE `id` = 1307;
DELETE FROM `iic_arctype` WHERE `id` = 1308;
DELETE FROM `iic_arctype` WHERE `id` = 1309;
DELETE FROM `iic_arctype` WHERE `id` = 1312;
DELETE FROM `iic_arctype` WHERE `id` = 1313;
DELETE FROM `iic_arctype` WHERE `id` = 1297;
