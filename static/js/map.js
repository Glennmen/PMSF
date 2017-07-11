//
// Global map.js variables
//

var $selectExclude
var $selectPokemonNotify
var $selectRarityNotify
var $textPerfectionNotify
var $raidNotify
var $selectStyle
var $selectIconResolution
var $selectIconSize
var $switchOpenGymsOnly
var $selectTeamGymsOnly
var $selectLastUpdateGymsOnly
var $switchActiveRaids
var $selectMinGymLevel
var $selectMaxGymLevel
var $selectMinRaidLevel
var $selectMaxRaidLevel
var $selectLuredPokestopsOnly
var $selectGymMarkerStyle
var $selectLocationIconMarker
var $switchGymSidebar

var language = document.documentElement.lang === '' ? 'en' : document.documentElement.lang
var idToPokemon = {}
var i8lnDictionary = {}
var languageLookups = 0
var languageLookupThreshold = 3

var searchMarkerStyles

var timestamp
var excludedPokemon = []
var notifiedPokemon = []
var notifiedRarity = []
var notifiedMinPerfection = null
var onlyPokemon = 0

var buffer = []
var reincludedPokemon = []
var reids = []

var map
var rawDataIsLoading = false
var locationMarker
var rangeMarkers = ['pokemon', 'pokestop', 'gym']
var storeZoom = true
var scanPath
var moves

var oSwLat
var oSwLng
var oNeLat
var oNeLng

var lastpokestops
var lastgyms
var lastpokemon
var lastslocs
var lastspawns

var selectedStyle = 'light'

var updateWorker
var lastUpdateTime

var cries
var assetsPath = 'static/sounds/'

var gymTypes = ['Uncontested', 'Mystic', 'Valor', 'Instinct']
createjs.Sound.registerSound('static/sounds/ding.mp3', 'ding')

var assetsPath = 'static/sounds/'
var cries = [{
    'src': 'cries.ogg',
    'data': {
        'audioSprite': [
            {
                'id': '1',
                'startTime': 261000,
                'duration': 934.6031746031827
            },
            {
                'id': '2',
                'startTime': 408000,
                'duration': 882.3582766439699
            },
            {
                'id': '3',
                'startTime': 433000,
                'duration': 1207.4376417233452
            },
            {
                'id': '4',
                'startTime': 463000,
                'duration': 748.8435374149844
            },
            {
                'id': '5',
                'startTime': 488000,
                'duration': 835.9183673469488
            },
            {
                'id': '6',
                'startTime': 513000,
                'duration': 1050.7029478458207
            },
            {
                'id': '7',
                'startTime': 544000,
                'duration': 940.4081632652606
            },
            {
                'id': '8',
                'startTime': 572000,
                'duration': 1004.2630385487428
            },
            {
                'id': '9',
                'startTime': 603000,
                'duration': 1120.3628117914377
            },
            {
                'id': '10',
                'startTime': 26000,
                'duration': 545.6689342403643
            },
            {
                'id': '11',
                'startTime': 52000,
                'duration': 1242.2675736961467
            },
            {
                'id': '12',
                'startTime': 84000,
                'duration': 615.3287981859421
            },
            {
                'id': '13',
                'startTime': 110000,
                'duration': 893.9682539682536
            },
            {
                'id': '14',
                'startTime': 136000,
                'duration': 1033.2879818594165
            },
            {
                'id': '15',
                'startTime': 163000,
                'duration': 1044.897959183686
            },
            {
                'id': '16',
                'startTime': 189000,
                'duration': 394.7392290249354
            },
            {
                'id': '17',
                'startTime': 211000,
                'duration': 847.5283446711899
            },
            {
                'id': '18',
                'startTime': 234000,
                'duration': 1010.0680272108775
            },
            {
                'id': '19',
                'startTime': 259000,
                'duration': 452.7891156462829
            },
            {
                'id': '20',
                'startTime': 286000,
                'duration': 586.3038548752684
            },
            {
                'id': '21',
                'startTime': 312000,
                'duration': 940.4081632653174
            },
            {
                'id': '22',
                'startTime': 337000,
                'duration': 952.0181405895869
            },
            {
                'id': '23',
                'startTime': 361000,
                'duration': 1143.5827664399199
            },
            {
                'id': '24',
                'startTime': 389000,
                'duration': 957.8231292517216
            },
            {
                'id': '25',
                'startTime': 396000,
                'duration': 1317.7324263038486
            },
            {
                'id': '26',
                'startTime': 399000,
                'duration': 1131.9727891156504
            },
            {
                'id': '27',
                'startTime': 402000,
                'duration': 574.6938775509989
            },
            {
                'id': '28',
                'startTime': 404000,
                'duration': 725.6235827664455
            },
            {
                'id': '29',
                'startTime': 406000,
                'duration': 603.7188208616726
            },
            {
                'id': '30',
                'startTime': 410000,
                'duration': 748.8435374149844
            },
            {
                'id': '31',
                'startTime': 412000,
                'duration': 911.3832199546437
            },
            {
                'id': '32',
                'startTime': 414000,
                'duration': 603.7188208616726
            },
            {
                'id': '33',
                'startTime': 416000,
                'duration': 824.3083900226793
            },
            {
                'id': '34',
                'startTime': 418000,
                'duration': 1387.3922902494087
            },
            {
                'id': '35',
                'startTime': 421000,
                'duration': 586.3038548752684
            },
            {
                'id': '36',
                'startTime': 423000,
                'duration': 644.3537414966158
            },
            {
                'id': '37',
                'startTime': 425000,
                'duration': 1282.90249433104
            },
            {
                'id': '38',
                'startTime': 428000,
                'duration': 1282.90249433104
            },
            {
                'id': '39',
                'startTime': 431000,
                'duration': 394.7392290249354
            },
            {
                'id': '40',
                'startTime': 436000,
                'duration': 505.03401360543876
            },
            {
                'id': '41',
                'startTime': 438000,
                'duration': 1010.0680272108775
            },
            {
                'id': '42',
                'startTime': 441000,
                'duration': 1050.7029478458207
            },
            {
                'id': '43',
                'startTime': 444000,
                'duration': 882.3582766439699
            },
            {
                'id': '44',
                'startTime': 446000,
                'duration': 714.013605442176
            },
            {
                'id': '45',
                'startTime': 448000,
                'duration': 1271.2925170068274
            },
            {
                'id': '46',
                'startTime': 451000,
                'duration': 1637.0068027210891
            },
            {
                'id': '47',
                'startTime': 454000,
                'duration': 1787.9365079365357
            },
            {
                'id': '48',
                'startTime': 457000,
                'duration': 1137.777777777785
            },
            {
                'id': '49',
                'startTime': 460000,
                'duration': 1050.7029478458207
            },
            {
                'id': '50',
                'startTime': 465000,
                'duration': 1027.4829931972818
            },
            {
                'id': '51',
                'startTime': 468000,
                'duration': 1131.9727891156504
            },
            {
                'id': '52',
                'startTime': 471000,
                'duration': 632.7437641723463
            },
            {
                'id': '53',
                'startTime': 473000,
                'duration': 998.458049886608
            },
            {
                'id': '54',
                'startTime': 475000,
                'duration': 864.9433106576225
            },
            {
                'id': '55',
                'startTime': 477000,
                'duration': 789.4784580498708
            },
            {
                'id': '56',
                'startTime': 479000,
                'duration': 888.1632653061047
            },
            {
                'id': '57',
                'startTime': 481000,
                'duration': 957.8231292517216
            },
            {
                'id': '58',
                'startTime': 483000,
                'duration': 772.0634920634666
            },
            {
                'id': '59',
                'startTime': 485000,
                'duration': 1102.9478458049766
            },
            {
                'id': '60',
                'startTime': 490000,
                'duration': 684.9886621315022
            },
            {
                'id': '61',
                'startTime': 492000,
                'duration': 499.229024943304
            },
            {
                'id': '62',
                'startTime': 494000,
                'duration': 777.8684807256013
            },
            {
                'id': '63',
                'startTime': 496000,
                'duration': 1213.24263038548
            },
            {
                'id': '64',
                'startTime': 499000,
                'duration': 1259.682539682558
            },
            {
                'id': '65',
                'startTime': 502000,
                'duration': 1619.5918367346849
            },
            {
                'id': '66',
                'startTime': 505000,
                'duration': 818.5034013605446
            },
            {
                'id': '67',
                'startTime': 507000,
                'duration': 812.6984126984098
            },
            {
                'id': '68',
                'startTime': 509000,
                'duration': 928.7981859410479
            },
            {
                'id': '69',
                'startTime': 511000,
                'duration': 534.0589569161125
            },
            {
                'id': '70',
                'startTime': 516000,
                'duration': 922.9931972788563
            },
            {
                'id': '71',
                'startTime': 518000,
                'duration': 1114.557823129303
            },
            {
                'id': '72',
                'startTime': 521000,
                'duration': 1114.557823129303
            },
            {
                'id': '73',
                'startTime': 524000,
                'duration': 1387.3922902494087
            },
            {
                'id': '74',
                'startTime': 527000,
                'duration': 1358.367346938735
            },
            {
                'id': '75',
                'startTime': 530000,
                'duration': 1573.151927437607
            },
            {
                'id': '76',
                'startTime': 533000,
                'duration': 1137.7777777777283
            },
            {
                'id': '77',
                'startTime': 536000,
                'duration': 1544.1269841269332
            },
            {
                'id': '78',
                'startTime': 539000,
                'duration': 1282.902494331097
            },
            {
                'id': '79',
                'startTime': 542000,
                'duration': 499.22902494336086
            },
            {
                'id': '80',
                'startTime': 546000,
                'duration': 783.6734693877361
            },
            {
                'id': '81',
                'startTime': 548000,
                'duration': 1062.3129251700902
            },
            {
                'id': '82',
                'startTime': 551000,
                'duration': 1364.1723356008697
            },
            {
                'id': '83',
                'startTime': 554000,
                'duration': 568.8888888888641
            },
            {
                'id': '84',
                'startTime': 556000,
                'duration': 1085.5328798186292
            },
            {
                'id': '85',
                'startTime': 559000,
                'duration': 1219.0476190476147
            },
            {
                'id': '86',
                'startTime': 562000,
                'duration': 986.8480725623385
            },
            {
                'id': '87',
                'startTime': 564000,
                'duration': 1097.1428571428987
            },
            {
                'id': '88',
                'startTime': 567000,
                'duration': 609.5238095238074
            },
            {
                'id': '89',
                'startTime': 569000,
                'duration': 1056.5079365079555
            },
            {
                'id': '90',
                'startTime': 575000,
                'duration': 893.9682539682963
            },
            {
                'id': '91',
                'startTime': 577000,
                'duration': 1155.1927437641325
            },
            {
                'id': '92',
                'startTime': 580000,
                'duration': 1410.6122448979477
            },
            {
                'id': '93',
                'startTime': 583000,
                'duration': 1300.3174603175012
            },
            {
                'id': '94',
                'startTime': 586000,
                'duration': 812.6984126984098
            },
            {
                'id': '95',
                'startTime': 588000,
                'duration': 1410.6122448979477
            },
            {
                'id': '96',
                'startTime': 591000,
                'duration': 1520.9070294785079
            },
            {
                'id': '97',
                'startTime': 594000,
                'duration': 1602.1768707482806
            },
            {
                'id': '98',
                'startTime': 597000,
                'duration': 1265.4875283446927
            },
            {
                'id': '99',
                'startTime': 600000,
                'duration': 1242.2675736961537
            },
            {
                'id': '100',
                'startTime': 0,
                'duration': 1317.7324263038547
            },
            {
                'id': '101',
                'startTime': 3000,
                'duration': 1352.5623582766436
            },
            {
                'id': '102',
                'startTime': 6000,
                'duration': 1172.607709750567
            },
            {
                'id': '103',
                'startTime': 9000,
                'duration': 1602.1768707482984
            },
            {
                'id': '104',
                'startTime': 12000,
                'duration': 766.2585034013603
            },
            {
                'id': '105',
                'startTime': 14000,
                'duration': 835.9183673469381
            },
            {
                'id': '106',
                'startTime': 16000,
                'duration': 1015.8730158730158
            },
            {
                'id': '107',
                'startTime': 19000,
                'duration': 975.2380952380939
            },
            {
                'id': '108',
                'startTime': 21000,
                'duration': 789.478458049885
            },
            {
                'id': '109',
                'startTime': 23000,
                'duration': 1062.3129251700689
            },
            {
                'id': '110',
                'startTime': 28000,
                'duration': 1126.1678004535156
            },
            {
                'id': '111',
                'startTime': 31000,
                'duration': 1143.5827664399058
            },
            {
                'id': '112',
                'startTime': 34000,
                'duration': 1352.562358276643
            },
            {
                'id': '113',
                'startTime': 37000,
                'duration': 824.3083900226793
            },
            {
                'id': '114',
                'startTime': 39000,
                'duration': 940.4081632653032
            },
            {
                'id': '115',
                'startTime': 41000,
                'duration': 1033.2879818594095
            },
            {
                'id': '116',
                'startTime': 44000,
                'duration': 568.8888888888855
            },
            {
                'id': '117',
                'startTime': 46000,
                'duration': 516.6439909297083
            },
            {
                'id': '118',
                'startTime': 48000,
                'duration': 615.3287981859421
            },
            {
                'id': '119',
                'startTime': 50000,
                'duration': 981.0430839002252
            },
            {
                'id': '120',
                'startTime': 55000,
                'duration': 1027.4829931972818
            },
            {
                'id': '121',
                'startTime': 58000,
                'duration': 1155.1927437641752
            },
            {
                'id': '122',
                'startTime': 61000,
                'duration': 1050.7029478458066
            },
            {
                'id': '123',
                'startTime': 64000,
                'duration': 801.0884353741545
            },
            {
                'id': '124',
                'startTime': 66000,
                'duration': 2333.605442176875
            },
            {
                'id': '125',
                'startTime': 70000,
                'duration': 1462.8571428571463
            },
            {
                'id': '126',
                'startTime': 73000,
                'duration': 1108.7528344671255
            },
            {
                'id': '127',
                'startTime': 76000,
                'duration': 743.0385487528355
            },
            {
                'id': '128',
                'startTime': 78000,
                'duration': 1068.1179138321966
            },
            {
                'id': '129',
                'startTime': 81000,
                'duration': 1242.2675736961396
            },
            {
                'id': '130',
                'startTime': 86000,
                'duration': 1126.1678004535156
            },
            {
                'id': '131',
                'startTime': 89000,
                'duration': 847.5283446712041
            },
            {
                'id': '132',
                'startTime': 91000,
                'duration': 661.7687074829917
            },
            {
                'id': '133',
                'startTime': 93000,
                'duration': 806.8934240362751
            },
            {
                'id': '134',
                'startTime': 95000,
                'duration': 1648.6167800453586
            },
            {
                'id': '135',
                'startTime': 98000,
                'duration': 934.6031746031684
            },
            {
                'id': '136',
                'startTime': 100000,
                'duration': 1358.3673469387777
            },
            {
                'id': '137',
                'startTime': 103000,
                'duration': 1102.9478458049907
            },
            {
                'id': '138',
                'startTime': 106000,
                'duration': 841.7233560090693
            },
            {
                'id': '139',
                'startTime': 108000,
                'duration': 812.6984126984098
            },
            {
                'id': '140',
                'startTime': 112000,
                'duration': 772.063492063495
            },
            {
                'id': '141',
                'startTime': 114000,
                'duration': 835.9183673469346
            },
            {
                'id': '142',
                'startTime': 116000,
                'duration': 1439.6371882086214
            },
            {
                'id': '143',
                'startTime': 119000,
                'duration': 441.1791383219992
            },
            {
                'id': '144',
                'startTime': 121000,
                'duration': 1369.9773242630329
            },
            {
                'id': '145',
                'startTime': 124000,
                'duration': 934.6031746031684
            },
            {
                'id': '146',
                'startTime': 126000,
                'duration': 1352.562358276643
            },
            {
                'id': '147',
                'startTime': 129000,
                'duration': 760.4535147392255
            },
            {
                'id': '148',
                'startTime': 131000,
                'duration': 998.458049886608
            },
            {
                'id': '149',
                'startTime': 133000,
                'duration': 1085.5328798186008
            },
            {
                'id': '150',
                'startTime': 139000,
                'duration': 1602.176870748309
            },
            {
                'id': '151',
                'startTime': 142000,
                'duration': 1509.2970521541815
            },
            {
                'id': '152',
                'startTime': 145000,
                'duration': 429.5691609977439
            },
            {
                'id': '153',
                'startTime': 147000,
                'duration': 597.9138321995379
            },
            {
                'id': '154',
                'startTime': 149000,
                'duration': 928.7981859410479
            },
            {
                'id': '155',
                'startTime': 151000,
                'duration': 487.6190476190345
            },
            {
                'id': '156',
                'startTime': 153000,
                'duration': 847.5283446711899
            },
            {
                'id': '157',
                'startTime': 155000,
                'duration': 1892.4263038548759
            },
            {
                'id': '158',
                'startTime': 158000,
                'duration': 946.2131519274237
            },
            {
                'id': '159',
                'startTime': 160000,
                'duration': 1195.8276643991042
            },
            {
                'id': '160',
                'startTime': 166000,
                'duration': 1677.641723356004
            },
            {
                'id': '161',
                'startTime': 169000,
                'duration': 342.4943310657511
            },
            {
                'id': '162',
                'startTime': 171000,
                'duration': 429.5691609977439
            },
            {
                'id': '163',
                'startTime': 173000,
                'duration': 650.1587301587222
            },
            {
                'id': '164',
                'startTime': 175000,
                'duration': 1056.507936507927
            },
            {
                'id': '165',
                'startTime': 178000,
                'duration': 423.76417233560915
            },
            {
                'id': '166',
                'startTime': 180000,
                'duration': 528.2539682539777
            },
            {
                'id': '167',
                'startTime': 182000,
                'duration': 772.063492063495
            },
            {
                'id': '168',
                'startTime': 184000,
                'duration': 795.2834467120056
            },
            {
                'id': '169',
                'startTime': 186000,
                'duration': 1248.0725623582885
            },
            {
                'id': '170',
                'startTime': 191000,
                'duration': 864.9433106575941
            },
            {
                'id': '171',
                'startTime': 193000,
                'duration': 748.843537414956
            },
            {
                'id': '172',
                'startTime': 195000,
                'duration': 528.2539682539777
            },
            {
                'id': '173',
                'startTime': 197000,
                'duration': 435.3741496598502
            },
            {
                'id': '174',
                'startTime': 199000,
                'duration': 638.5487528344811
            },
            {
                'id': '175',
                'startTime': 201000,
                'duration': 679.1836734693959
            },
            {
                'id': '176',
                'startTime': 203000,
                'duration': 493.42403628116926
            },
            {
                'id': '177',
                'startTime': 205000,
                'duration': 655.9637188208569
            },
            {
                'id': '178',
                'startTime': 207000,
                'duration': 812.6984126984098
            },
            {
                'id': '179',
                'startTime': 209000,
                'duration': 563.0839002267578
            },
            {
                'id': '180',
                'startTime': 213000,
                'duration': 981.0430839002322
            },
            {
                'id': '181',
                'startTime': 215000,
                'duration': 946.2131519274237
            },
            {
                'id': '182',
                'startTime': 217000,
                'duration': 882.3582766439984
            },
            {
                'id': '183',
                'startTime': 219000,
                'duration': 743.0385487528213
            },
            {
                'id': '184',
                'startTime': 221000,
                'duration': 899.7732426303742
            },
            {
                'id': '185',
                'startTime': 223000,
                'duration': 876.5532879818636
            },
            {
                'id': '186',
                'startTime': 225000,
                'duration': 1073.9229024943313
            },
            {
                'id': '187',
                'startTime': 228000,
                'duration': 528.2539682539777
            },
            {
                'id': '188',
                'startTime': 230000,
                'duration': 743.0385487528213
            },
            {
                'id': '189',
                'startTime': 232000,
                'duration': 899.7732426303742
            },
            {
                'id': '190',
                'startTime': 237000,
                'duration': 818.5034013605446
            },
            {
                'id': '191',
                'startTime': 239000,
                'duration': 563.0839002267578
            },
            {
                'id': '192',
                'startTime': 241000,
                'duration': 893.9682539682678
            },
            {
                'id': '193',
                'startTime': 243000,
                'duration': 946.2131519274237
            },
            {
                'id': '194',
                'startTime': 245000,
                'duration': 499.229024943304
            },
            {
                'id': '195',
                'startTime': 247000,
                'duration': 754.6485260770908
            },
            {
                'id': '196',
                'startTime': 249000,
                'duration': 1114.5578231292461
            },
            {
                'id': '197',
                'startTime': 252000,
                'duration': 888.1632653061331
            },
            {
                'id': '198',
                'startTime': 254000,
                'duration': 917.1882086167784
            },
            {
                'id': '199',
                'startTime': 256000,
                'duration': 1253.8775510204232
            },
            {
                'id': '200',
                'startTime': 263000,
                'duration': 725.6235827664455
            },
            {
                'id': '201',
                'startTime': 265000,
                'duration': 719.8185941043107
            },
            {
                'id': '202',
                'startTime': 267000,
                'duration': 1143.5827664399199
            },
            {
                'id': '203',
                'startTime': 270000,
                'duration': 876.5532879818352
            },
            {
                'id': '204',
                'startTime': 272000,
                'duration': 772.0634920634666
            },
            {
                'id': '205',
                'startTime': 274000,
                'duration': 1033.2879818594165
            },
            {
                'id': '206',
                'startTime': 277000,
                'duration': 888.1632653061047
            },
            {
                'id': '207',
                'startTime': 279000,
                'duration': 632.7437641723463
            },
            {
                'id': '208',
                'startTime': 281000,
                'duration': 1770.5215419501314
            },
            {
                'id': '209',
                'startTime': 284000,
                'duration': 841.7233560090835
            },
            {
                'id': '210',
                'startTime': 288000,
                'duration': 1190.022675736941
            },
            {
                'id': '211',
                'startTime': 291000,
                'duration': 696.5986394557717
            },
            {
                'id': '212',
                'startTime': 293000,
                'duration': 1178.4126984126715
            },
            {
                'id': '213',
                'startTime': 296000,
                'duration': 632.7437641723463
            },
            {
                'id': '214',
                'startTime': 298000,
                'duration': 952.0181405895869
            },
            {
                'id': '215',
                'startTime': 300000,
                'duration': 539.8639455782472
            },
            {
                'id': '216',
                'startTime': 302000,
                'duration': 876.5532879818352
            },
            {
                'id': '217',
                'startTime': 304000,
                'duration': 1393.1972789115434
            },
            {
                'id': '218',
                'startTime': 307000,
                'duration': 986.8480725623385
            },
            {
                'id': '219',
                'startTime': 309000,
                'duration': 1091.3378684807071
            },
            {
                'id': '220',
                'startTime': 314000,
                'duration': 754.6485260771192
            },
            {
                'id': '221',
                'startTime': 316000,
                'duration': 719.8185941043107
            },
            {
                'id': '222',
                'startTime': 318000,
                'duration': 673.3786848072327
            },
            {
                'id': '223',
                'startTime': 320000,
                'duration': 644.3537414966158
            },
            {
                'id': '224',
                'startTime': 322000,
                'duration': 1271.2925170068274
            },
            {
                'id': '225',
                'startTime': 325000,
                'duration': 766.2585034013887
            },
            {
                'id': '226',
                'startTime': 327000,
                'duration': 922.9931972789132
            },
            {
                'id': '227',
                'startTime': 329000,
                'duration': 1207.4376417233452
            },
            {
                'id': '228',
                'startTime': 332000,
                'duration': 766.2585034013887
            },
            {
                'id': '229',
                'startTime': 334000,
                'duration': 1102.9478458049766
            },
            {
                'id': '230',
                'startTime': 339000,
                'duration': 847.5283446712183
            },
            {
                'id': '231',
                'startTime': 341000,
                'duration': 667.573696145098
            },
            {
                'id': '232',
                'startTime': 343000,
                'duration': 1108.7528344671114
            },
            {
                'id': '233',
                'startTime': 346000,
                'duration': 917.1882086167784
            },
            {
                'id': '234',
                'startTime': 348000,
                'duration': 1108.7528344671114
            },
            {
                'id': '235',
                'startTime': 351000,
                'duration': 655.9637188208853
            },
            {
                'id': '236',
                'startTime': 353000,
                'duration': 917.1882086167784
            },
            {
                'id': '237',
                'startTime': 355000,
                'duration': 789.4784580498708
            },
            {
                'id': '238',
                'startTime': 357000,
                'duration': 731.4285714285802
            },
            {
                'id': '239',
                'startTime': 359000,
                'duration': 644.3537414966158
            },
            {
                'id': '240',
                'startTime': 364000,
                'duration': 841.7233560090835
            },
            {
                'id': '241',
                'startTime': 366000,
                'duration': 882.3582766439699
            },
            {
                'id': '242',
                'startTime': 368000,
                'duration': 917.1882086167784
            },
            {
                'id': '243',
                'startTime': 370000,
                'duration': 1248.0725623582885
            },
            {
                'id': '244',
                'startTime': 373000,
                'duration': 1271.2925170068274
            },
            {
                'id': '245',
                'startTime': 376000,
                'duration': 1033.2879818594165
            },
            {
                'id': '246',
                'startTime': 379000,
                'duration': 783.6734693877361
            },
            {
                'id': '247',
                'startTime': 381000,
                'duration': 568.8888888888641
            },
            {
                'id': '248',
                'startTime': 383000,
                'duration': 1317.7324263038486
            },
            {
                'id': '249',
                'startTime': 386000,
                'duration': 1828.571428571422
            },
            {
                'id': '250',
                'startTime': 391000,
                'duration': 1160.997732426324
            },
            {
                'id': '251',
                'startTime': 394000,
                'duration': 859.1383219954878
            }
        ]
    }
}]

createjs.Sound.alternateExtensions = ['mp3']
// createjs.Sound.on('fileload', loadSound)
createjs.Sound.registerSounds(cries, assetsPath)

var genderType = ['♂', '♀', '⚲']
var unownForm = ['unset', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '!', '?']

/*
 text place holders:
 <pkm> - pokemon name
 <prc> - iv in percent without percent symbol
 <atk> - attack as number
 <def> - defense as number
 <sta> - stamnia as number
 */
var notifyIvTitle = '<pkm> <prc>% (<atk>/<def>/<sta>)'
var notifyNoIvTitle = '<pkm>'

/*
 text place holders:
 <dist>  - disappear time
 <udist> - time until disappear
 */
var notifyText = 'disappears at <dist> (<udist>)'

//
// Functions
//

function excludePokemon(id) { // eslint-disable-line no-unused-vars
    $selectExclude.val(
        $selectExclude.val().concat(id)
    ).trigger('change')
}

function notifyAboutPokemon(id) { // eslint-disable-line no-unused-vars
    $selectPokemonNotify.val(
        $selectPokemonNotify.val().concat(id)
    ).trigger('change')
}

function removePokemonMarker(encounterId) { // eslint-disable-line no-unused-vars
    if (mapData.pokemons[encounterId].marker.rangeCircle) {
        mapData.pokemons[encounterId].marker.rangeCircle.setMap(null)
        delete mapData.pokemons[encounterId].marker.rangeCircle
    }
    mapData.pokemons[encounterId].marker.setMap(null)
    mapData.pokemons[encounterId].hidden = true
}

function initMap() { // eslint-disable-line no-unused-vars
    map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: centerLat,
            lng: centerLng
        },
        zoom: zoom == null ? Store.get('zoomLevel') : zoom,
        minZoom: minZoom,
        fullscreenControl: true,
        streetViewControl: false,
        mapTypeControl: false,
        clickableIcons: false,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
            position: google.maps.ControlPosition.RIGHT_TOP,
            mapTypeIds: [
                google.maps.MapTypeId.ROADMAP,
                google.maps.MapTypeId.SATELLITE,
                google.maps.MapTypeId.HYBRID,
                'nolabels_style',
                'dark_style',
                'style_light2',
                'style_pgo',
                'dark_style_nl',
                'style_light2_nl',
                'style_pgo_nl',
                'style_pgo_day',
                'style_pgo_night',
                'style_pgo_dynamic'
            ]
        }
    })

    var styleNoLabels = new google.maps.StyledMapType(noLabelsStyle, {
        name: 'No Labels'
    })
    map.mapTypes.set('nolabels_style', styleNoLabels)

    var styleDark = new google.maps.StyledMapType(darkStyle, {
        name: 'Dark'
    })
    map.mapTypes.set('dark_style', styleDark)

    var styleLight2 = new google.maps.StyledMapType(light2Style, {
        name: 'Light2'
    })
    map.mapTypes.set('style_light2', styleLight2)

    var stylePgo = new google.maps.StyledMapType(pGoStyle, {
        name: 'PokemonGo'
    })
    map.mapTypes.set('style_pgo', stylePgo)

    var styleDarkNl = new google.maps.StyledMapType(darkStyleNoLabels, {
        name: 'Dark (No Labels)'
    })
    map.mapTypes.set('dark_style_nl', styleDarkNl)

    var styleLight2Nl = new google.maps.StyledMapType(light2StyleNoLabels, {
        name: 'Light2 (No Labels)'
    })
    map.mapTypes.set('style_light2_nl', styleLight2Nl)

    var stylePgoNl = new google.maps.StyledMapType(pGoStyleNoLabels, {
        name: 'PokemonGo (No Labels)'
    })
    map.mapTypes.set('style_pgo_nl', stylePgoNl)

    var stylePgoDay = new google.maps.StyledMapType(pGoStyleDay, {
        name: 'PokemonGo Day'
    })
    map.mapTypes.set('style_pgo_day', stylePgoDay)

    var stylePgoNight = new google.maps.StyledMapType(pGoStyleNight, {
        name: 'PokemonGo Night'
    })
    map.mapTypes.set('style_pgo_night', stylePgoNight)

    // dynamic map style chooses stylePgoDay or stylePgoNight depending on client time
    var currentDate = new Date()
    var currentHour = currentDate.getHours()
    var stylePgoDynamic = currentHour >= 6 && currentHour < 19 ? stylePgoDay : stylePgoNight
    map.mapTypes.set('style_pgo_dynamic', stylePgoDynamic)

    map.addListener('maptypeid_changed', function (s) {
        Store.set('map_style', this.mapTypeId)
    })

    map.setMapTypeId(Store.get('map_style'))
    map.addListener('idle', updateMap)

    map.addListener('zoom_changed', function () {
        if (storeZoom === true) {
            Store.set('zoomLevel', this.getZoom())
        } else {
            storeZoom = true
        }

        redrawPokemon(mapData.pokemons)
        redrawPokemon(mapData.lurePokemons)
    })

    createMyLocationButton()
    initSidebar()
}

function updateLocationMarker(style) {
    if (style in searchMarkerStyles) {
        locationMarker.setIcon(searchMarkerStyles[style].icon)
        Store.set('locationMarkerStyle', style)
    }
    return locationMarker
}

function createLocationMarker() {
    var position = Store.get('followMyLocationPosition')
    var lat = 'lat' in position ? position.lat : centerLat
    var lng = 'lng' in position ? position.lng : centerLng

    var locationMarker = new google.maps.Marker({
        map: map,
        animation: google.maps.Animation.DROP,
        position: {
            lat: lat,
            lng: lng
        },
        draggable: false,
        icon: null,
        optimized: false,
        zIndex: google.maps.Marker.MAX_ZINDEX + 2
    })

    locationMarker.setIcon(searchMarkerStyles[Store.get('locationMarkerStyle')].icon)

    locationMarker.infoWindow = new google.maps.InfoWindow({
        content: '<div><b>My Location</b></div>',
        disableAutoPan: true
    })

    addListeners(locationMarker)

    google.maps.event.addListener(locationMarker, 'dragend', function () {
        var newLocation = locationMarker.getPosition()
        Store.set('followMyLocationPosition', {
            lat: newLocation.lat(),
            lng: newLocation.lng()
        })
    })

    return locationMarker
}

function initSidebar() {
    $('#gyms-switch').prop('checked', Store.get('showGyms'))
    $('#gym-sidebar-switch').prop('checked', Store.get('useGymSidebar'))
    $('#gyms-filter-wrapper').toggle(Store.get('showGyms'))
    $('#team-gyms-only-switch').val(Store.get('showTeamGymsOnly'))
    $('#open-gyms-only-switch').prop('checked', Store.get('showOpenGymsOnly'))
    $('#raids-switch').prop('checked', Store.get('showRaids'))
    $('#raids-filter-wrapper').toggle(Store.get('showRaids'))
    $('#active-raids-switch').prop('checked', Store.get('activeRaids'))
    $('#min-level-gyms-filter-switch').val(Store.get('minGymLevel'))
    $('#max-level-gyms-filter-switch').val(Store.get('maxGymLevel'))
    $('#min-level-raids-filter-switch').val(Store.get('minRaidLevel'))
    $('#max-level-raids-filter-switch').val(Store.get('maxRaidLevel'))
    $('#last-update-gyms-switch').val(Store.get('showLastUpdatedGymsOnly'))
    $('#pokemon-switch').prop('checked', Store.get('showPokemon'))
    $('#pokestops-switch').prop('checked', Store.get('showPokestops'))
    $('#lured-pokestops-only-switch').val(Store.get('showLuredPokestopsOnly'))
    $('#lured-pokestops-only-wrapper').toggle(Store.get('showPokestops'))
    $('#start-at-user-location-switch').prop('checked', Store.get('startAtUserLocation'))
    $('#start-at-last-location-switch').prop('checked', Store.get('startAtLastLocation'))
    $('#follow-my-location-switch').prop('checked', Store.get('followMyLocation'))
    $('#spawn-area-switch').prop('checked', Store.get('spawnArea'))
    $('#scanned-switch').prop('checked', Store.get('showScanned'))
    $('#spawnpoints-switch').prop('checked', Store.get('showSpawnpoints'))
    $('#ranges-switch').prop('checked', Store.get('showRanges'))
    $('#sound-switch').prop('checked', Store.get('playSound'))
    $('#cries-switch').prop('checked', Store.get('playCries'))
    $('#cries-switch-wrapper').toggle(Store.get('playSound'))
    if (document.getElementById('next-location')) {
        var searchBox = new google.maps.places.Autocomplete(document.getElementById('next-location'))
        $('#next-location').css('background-color', $('#geoloc-switch').prop('checked') ? '#e0e0e0' : '#ffffff')

        searchBox.addListener('place_changed', function () {
            var place = searchBox.getPlace()

            if (!place.geometry) return

            var loc = place.geometry.location
            changeLocation(loc.lat(), loc.lng())
        })
    }

    var icons = $('#pokemon-icons')
    $.each(pokemonSprites, function (key, value) {
        icons.append($('<option></option>').attr('value', key).text(value.name))
    })
    icons.val(pokemonSprites[Store.get('pokemonIcons')] ? Store.get('pokemonIcons') : 'highres')

    $('#pokemon-icon-size').val(Store.get('iconSizeModifier'))
}

function pad(number) {
    return number <= 99 ? ('0' + number).slice(-2) : number
}

function getTypeSpan(type) {
    return '<span style="padding: 2px 5px; text-transform: uppercase; color: white; margin-right: 2px; border-radius: 4px; font-size: 0.8em; vertical-align: text-bottom; background-color: ' + type['color'] + ';">' + type['type'] + '</span>'
}

function openMapDirections(lat, lng) { // eslint-disable-line no-unused-vars
    var url = 'https://www.google.com/maps/?daddr=' + lat + ',' + lng
    window.open(url, '_blank')
}

// Converts timestamp to readable String
function getDateStr(t) {
    var dateStr = 'Unknown'
    if (t) {
        dateStr = moment(t).format('DD-MM-YYYY, HH:mm:ss')
    }
    return dateStr
}

// Converts timestamp to readable String
function getTimeStr(t) {
    var dateStr = 'Unknown'
    if (t) {
        dateStr = moment(t).format('HH:mm:ss')
    }
    return dateStr
}

function toggleOtherPokemon(pokemonId) { // eslint-disable-line no-unused-vars
    onlyPokemon = onlyPokemon === 0 ? pokemonId : 0
    if (onlyPokemon === 0) {
        // reload all Pokemon
        lastpokemon = false
        updateMap()
    } else {
        // remove other Pokemon
        clearStaleMarkers()
    }
}

function isTemporaryHidden(pokemonId) {
    return onlyPokemon !== 0 && pokemonId !== onlyPokemon
}

function pokemonLabel(item) {
    var name = item['pokemon_name']
    var rarityDisplay = item['pokemon_rarity'] ? '(' + item['pokemon_rarity'] + ')' : ''
    var types = item['pokemon_types']
    var typesDisplay = ''
    var encounterId = item['encounter_id']
    var id = item['pokemon_id']
    var latitude = item['latitude']
    var longitude = item['longitude']
    var disappearTime = item['disappear_time']
    var disappearDate = new Date(disappearTime)
    var atk = item['individual_attack']
    var def = item['individual_defense']
    var sta = item['individual_stamina']
    var pMove1 = moves[item['move_1']] !== undefined ? i8ln(moves[item['move_1']]['name']) : 'gen/unknown'
    var pMove2 = moves[item['move_2']] !== undefined ? i8ln(moves[item['move_2']]['name']) : 'gen/unknown'
    var weight = item['weight']
    var height = item['height']
    var gender = item['gender']
    var form = item['form']
    var cp = item['cp']
    var cpMultiplier = item['cp_multiplier']
    var level = item['level']

    $.each(types, function (index, type) {
        typesDisplay += getTypeSpan(type)
    })

    var details = ''
    if (atk != null && def != null && sta != null) {
        var iv = getIv(atk, def, sta)
        details =
            '<div>' +
            'IV: ' + iv.toFixed(1) + '% (' + atk + '/' + def + '/' + sta + ')' +
            '</div>'

        if (cp != null && (cpMultiplier != null || level != null)) {
            var pokemonLevel
            if (level != null) {
                pokemonLevel = level
            } else {
                pokemonLevel = getPokemonLevel(cpMultiplier)
            }
            details +=
                '<div>' +
                'CP: ' + cp + ' | Level: ' + pokemonLevel +
                '</div>'
        }

        details +=
            '<div>' +
            'Moves: ' + pMove1 + ' / ' + pMove2 +
            '</div>'
    }
    if (gender != null) {
        details +=
            '<div>' +
            'Gender: ' + genderType[gender - 1]
        if (weight != null && height != null) {
            details += ' | Weight: ' + weight.toFixed(2) + 'kg | Height: ' + height.toFixed(2) + 'm'
        }
        details +=
            '</div>'
    }
    var contentstring =
        '<div>' +
        '<b>' + name + '</b>'
    if (id === 201 && form !== null && form > 0) {
        contentstring += ' (' + unownForm[item['form']] + ')'
    }
    contentstring += '<span> - </span>' +
        '<small>' +
        '<a href="https://pokemon.gameinfo.io/en/pokemon/' + id + '" target="_blank" title="View in Pokedex">#' + id + '</a>' +
        '</small>' +
        '<span> ' + rarityDisplay + '</span>' +
        '<span> - </span>' +
        '<small>' + typesDisplay + '</small>' +
        '</div>' +
        '<div>' +
        'Disappears at ' + pad(disappearDate.getHours()) + ':' + pad(disappearDate.getMinutes()) + ':' + pad(disappearDate.getSeconds()) +
        ' <span class="label-countdown" disappears-at="' + disappearTime + '">(00m00s)</span>' +
        '</div>' +
        '<div>' +
        'Location: <a href="javascript:void(0)" onclick="javascript:openMapDirections(' + latitude + ', ' + longitude + ')" title="View in Maps">' + latitude.toFixed(6) + ', ' + longitude.toFixed(7) + '</a>' +
        '</div>' +
        details +
        '<div>' +
        '<a href="javascript:excludePokemon(' + id + ')">Exclude</a>&nbsp&nbsp' +
        '<a href="javascript:notifyAboutPokemon(' + id + ')">Notify</a>&nbsp&nbsp' +
        '<a href="javascript:removePokemonMarker(\'' + encounterId + '\')">Remove</a>&nbsp&nbsp' +
        '<a href="javascript:void(0);" onclick="javascript:toggleOtherPokemon(' + id + ');" title="Toggle display of other Pokemon">Toggle Others</a>' +
        '</div>'
    return contentstring
}

function gymLabel(item) {
    var teamName = gymTypes[item['team_id']]
    var teamId = item['team_id']
    var latitude = item['latitude']
    var longitude = item['longitude']
    var lastScanned = item['last_scanned']
    var lastModified = item['last_modified']
    var name = item['name']
    var members = item['pokemon']

    var raidSpawned = item['raid_level'] != null
    var raidStarted = item['raid_pokemon_id'] != null

    var raidStr = ''
    var raidIcon = ''
    if (raidSpawned && item.raid_end > Date.now()) {
        var levelStr = ''
        for (i = 0; i < item['raid_level']; i++) {
            levelStr += '★'
        }
        raidStr = '<h3 style="margin-bottom: 0">Raid ' + levelStr
        if (raidStarted) {
            var cpStr = ''
            if (item.raid_pokemon_cp != null) {
                cpStr = ' CP ' + item.raid_pokemon_cp
            }
            raidStr += '<br>' + item.raid_pokemon_name + cpStr
        }
        raidStr += '</h3>'
        if (raidStarted && item.raid_pokemon_move_1 != null && item.raid_pokemon_move_2 != null) {
            var pMove1 = (moves[item['raid_pokemon_move_1']] !== undefined) ? i8ln(moves[item['raid_pokemon_move_1']]['name']) : 'gen/unknown'
            var pMove2 = (moves[item['raid_pokemon_move_2']] !== undefined) ? i8ln(moves[item['raid_pokemon_move_2']]['name']) : 'gen/unknown'
            raidStr += '<div><b>' + pMove1 + ' / ' + pMove2 + '</b></div>'
        }

        var raidStartStr = getTimeStr(item['raid_start'])
        var raidEndStr = getTimeStr(item['raid_end'])
        raidStr += '<div>Start: <b>' + raidStartStr + '</b> <span class="label-countdown" disappears-at="' + item['raid_start'] + '" start>(00m00s)</span></div>'
        raidStr += '<div>End: <b>' + raidEndStr + '</b> <span class="label-countdown" disappears-at="' + item['raid_end'] + '" end>(00m00s)</span></div>'

        if (raidStarted) {
            raidIcon = '<i class="pokemon-large-raid-sprite n' + item.raid_pokemon_id + '"></i>'
        } else {
            var raidEgg = ''
            if (item['raid_level'] <= 2) {
                raidEgg = 'normal'
            } else if (item['raid_level'] <= 4) {
                raidEgg = 'rare'
            } else {
                raidEgg = 'legendary'
            }
            raidIcon = '<img src="static/raids/egg_' + raidEgg + '.png">'
        }
    }

    var memberStr = ''
    for (var i = 0; i < members.length; i++) {
        memberStr +=
            '<span class="gym-member" title="' + members[i].pokemon_name + ' | ' + members[i].trainer_name + ' (Lvl ' + members[i].trainer_level + ')">' +
            '<i class="pokemon-sprite n' + members[i].pokemon_id + '"></i>' +
            '<span class="cp team-' + teamId + '">' + members[i].pokemon_cp + '</span>' +
            '</span>'
    }

    var lastScannedStr = ''
    if (lastScanned != null) {
        lastScannedStr =
            '<div>' +
            'Last Scanned: ' + getDateStr(lastScanned) +
            '</div>'
    }

    var lastModifiedStr = getDateStr(lastModified)

    var nameStr = (name ? '<div>' + name + '</div>' : '')

    var gymColor = ['0, 0, 0, .4', '74, 138, 202, .6', '240, 68, 58, .6', '254, 217, 40, .6']
    var str
    if (teamId === 0) {
        str =
            '<div>' +
            '<center>' +
            '<div>' +
            '<b style="color:rgba(' + gymColor[teamId] + ')">' + teamName + '</b><br>' +
            '<img height="70px" style="padding: 5px;" src="static/forts/' + teamName + '_large.png">' +
            raidIcon +
            '</div>' +
            nameStr +
            raidStr +
            '<div>' +
            'Location: <a href="javascript:void(0);" onclick="javascript:openMapDirections(' + latitude + ',' + longitude + ');" title="View in Maps">' + latitude.toFixed(6) + ' , ' + longitude.toFixed(7) + '</a>' +
            '</div>' +
            '<div>' +
            lastScannedStr +
            '</div>' +
            '<div>' +
            'Last Modified: ' + lastModifiedStr +
            '</div>' +
            '</center>' +
            '</div>'
    } else {
        var freeSlots = item['slots_available']
        var gymCp = ''
        if (item['total_gym_cp'] != null) {
            gymCp = '<div>Total Gym CP: <b>' + item.total_gym_cp + '</b></div>'
        }
        str =
            '<div>' +
            '<center>' +
            '<div style="padding-bottom: 2px">' +
            'Gym owned by:' +
            '</div>' +
            '<div>' +
            '<b style="color:rgba(' + gymColor[teamId] + ')">Team ' + teamName + '</b><br>' +
            '<img height="70px" style="padding: 5px;" src="static/forts/' + teamName + '_large.png">' +
            raidIcon +
            '</div>' +
            nameStr +
            raidStr +
            '<div><b>' + freeSlots + ' Free Slots</b></div>' +
            gymCp +
            '<div>' +
            memberStr +
            '</div>' +
            '<div>' +
            'Location: <a href="javascript:void(0);" onclick="javascript:openMapDirections(' + latitude + ',' + longitude + ');" title="View in Maps">' + latitude.toFixed(6) + ' , ' + longitude.toFixed(7) + '</a>' +
            '</div>' +
            '<div>' +
            lastScannedStr +
            '</div>' +
            '<div>' +
            'Last Modified: ' + lastModifiedStr +
            '</div>' +
            '</center>' +
            '</div>'
    }

    return str
}

function pokestopLabel(expireTime, latitude, longitude) {
    var str
    if (expireTime) {
        var expireDate = new Date(expireTime)

        str =
            '<div>' +
            '<b>Lured Pokéstop</b>' +
            '</div>' +
            '<div>' +
            'Lure expires at ' + pad(expireDate.getHours()) + ':' + pad(expireDate.getMinutes()) + ':' + pad(expireDate.getSeconds()) +
            ' <span class="label-countdown" disappears-at="' + expireTime + '">(00m00s)</span>' +
            '</div>' +
            '<div>' +
            'Location: <a href="javascript:void(0)" onclick="javascript:openMapDirections(' + latitude + ',' + longitude + ')" title="View in Maps">' + latitude.toFixed(6) + ', ' + longitude.toFixed(7) + '</a>' +
            '</div>'
    } else {
        str =
            '<div>' +
            '<b>Pokéstop</b>' +
            '</div>' +
            '<div>' +
            'Location: <a href="javascript:void(0)" onclick="javascript:openMapDirections(' + latitude + ',' + longitude + ')" title="View in Maps">' + latitude.toFixed(6) + ', ' + longitude.toFixed(7) + '</a>' +
            '</div>'
    }

    return str
}

function formatSpawnTime(seconds) {
    // the addition and modulo are required here because the db stores when a spawn disappears
    // the subtraction to get the appearance time will knock seconds under 0 if the spawn happens in the previous hour
    return ('0' + Math.floor((seconds + 3600) % 3600 / 60)).substr(-2) + ':' + ('0' + seconds % 60).substr(-2)
}

function spawnpointLabel(item) {
    var str =
        '<div>' +
        '<b>Spawn Point</b>' +
        '</div>' +
        '<div>' +
        'Every hour from ' + formatSpawnTime(item.time) + ' to ' + formatSpawnTime(item.time + 900) +
        '</div>'

    if (item.special) {
        str +=
            '<div>' +
            'May appear as early as ' + formatSpawnTime(item.time - 1800) +
            '</div>'
    }
    return str
}

function addRangeCircle(marker, map, type, teamId) {
    var targetmap = null
    var circleCenter = new google.maps.LatLng(marker.position.lat(), marker.position.lng())
    var gymColors = ['#999999', '#0051CF', '#FF260E', '#FECC23'] // 'Uncontested', 'Mystic', 'Valor', 'Instinct']
    var teamColor = gymColors[0]
    if (teamId) teamColor = gymColors[teamId]

    var range
    var circleColor

    // handle each type of marker and be explicit about the range circle attributes
    switch (type) {
        case 'pokemon':
            circleColor = '#C233F2'
            range = 40 // pokemon appear at 40m and then you can move away. still have to be 40m close to see it though, so ignore the further disappear distance
            break
        case 'pokestop':
            circleColor = '#3EB0FF'
            range = 40
            break
        case 'gym':
            circleColor = teamColor
            range = 40
            break
    }

    if (map) targetmap = map

    var rangeCircleOpts = {
        map: targetmap,
        radius: range, // meters
        strokeWeight: 1,
        strokeColor: circleColor,
        strokeOpacity: 0.9,
        center: circleCenter,
        fillColor: circleColor,
        fillOpacity: 0.3
    }
    var rangeCircle = new google.maps.Circle(rangeCircleOpts)
    return rangeCircle
}

function isRangeActive(map) {
    if (map.getZoom() < 16) return false
    return Store.get('showRanges')
}

function getIv(atk, def, stm) {
    if (atk !== null) {
        return 100.0 * (atk + def + stm) / 45
    }

    return false
}

function getPokemonLevel(cpMultiplier) {
    if (cpMultiplier < 0.734) {
        var pokemonLevel = 58.35178527 * cpMultiplier * cpMultiplier - 2.838007664 * cpMultiplier + 0.8539209906
    } else {
        pokemonLevel = 171.0112688 * cpMultiplier - 95.20425243
    }
    pokemonLevel = Math.round(pokemonLevel) * 2 / 2

    return pokemonLevel
}

function lpad(str, len, padstr) {
    return Array(Math.max(len - String(str).length + 1, 0)).join(padstr) + str
}

function repArray(text, find, replace) {
    for (var i = 0; i < find.length; i++) {
        text = text.replace(find[i], replace[i])
    }

    return text
}

function getTimeUntil(time) {
    var now = +new Date()
    var tdiff = time - now

    var sec = Math.floor(tdiff / 1000 % 60)
    var min = Math.floor(tdiff / 1000 / 60 % 60)
    var hour = Math.floor(tdiff / (1000 * 60 * 60) % 24)

    return {
        'total': tdiff,
        'hour': hour,
        'min': min,
        'sec': sec,
        'now': now,
        'ttime': time
    }
}

function getNotifyText(item) {
    var iv = getIv(item['individual_attack'], item['individual_defense'], item['individual_stamina'])
    var find = ['<prc>', '<pkm>', '<atk>', '<def>', '<sta>']
    var replace = [iv ? iv.toFixed(1) : '', item['pokemon_name'], item['individual_attack'], item['individual_defense'], item['individual_stamina']]
    var ntitle = repArray(iv ? notifyIvTitle : notifyNoIvTitle, find, replace)
    var dist = new Date(item['disappear_time']).toLocaleString([], {
        hour: '2-digit', minute: '2-digit',
        second: '2-digit', hour12: false
    })
    var until = getTimeUntil(item['disappear_time'])
    var udist = until.hour > 0 ? until.hour + ':' : ''
    udist += lpad(until.min, 2, 0) + 'm' + lpad(until.sec, 2, 0) + 's'
    find = ['<dist>', '<udist>']
    replace = [dist, udist]
    var ntext = repArray(notifyText, find, replace)

    return {
        'fav_title': ntitle,
        'fav_text': ntext
    }
}

function customizePokemonMarker(marker, item, skipNotification) {
    marker.addListener('click', function () {
        this.setAnimation(null)
        this.animationDisabled = true
    })

    if (!marker.rangeCircle && isRangeActive(map)) {
        marker.rangeCircle = addRangeCircle(marker, map, 'pokemon')
    }

    marker.infoWindow = new google.maps.InfoWindow({
        content: pokemonLabel(item),
        disableAutoPan: true
    })

    if (notifiedPokemon.indexOf(item['pokemon_id']) > -1 || notifiedRarity.indexOf(item['pokemon_rarity']) > -1) {
        if (!skipNotification) {
            checkAndCreateSound(item['pokemon_id'])
            sendNotification(getNotifyText(item).fav_title, getNotifyText(item).fav_text, 'static/icons/' + item['pokemon_id'] + '.png', item['latitude'], item['longitude'])
        }
        if (marker.animationDisabled !== true) {
            marker.setAnimation(google.maps.Animation.BOUNCE)
        }
    }

    if (item['individual_attack'] != null) {
        var perfection = getIv(item['individual_attack'], item['individual_defense'], item['individual_stamina'])
        if (notifiedMinPerfection > 0 && perfection >= notifiedMinPerfection) {
            if (!skipNotification) {
                checkAndCreateSound(item['pokemon_id'])
                sendNotification(getNotifyText(item).fav_title, getNotifyText(item).fav_text, 'static/icons/' + item['pokemon_id'] + '.png', item['latitude'], item['longitude'])
            }
            if (marker.animationDisabled !== true) {
                marker.setAnimation(google.maps.Animation.BOUNCE)
            }
        }
    }

    addListeners(marker)
}

function getGymMarkerIcon(item) {
    var level = item.raid_level
    var team = item.team_id
    var teamStr = ''
    if (team === 0) {
        teamStr = gymTypes[item['team_id']]
    } else {
        teamStr = gymTypes[item['team_id']] + '_' + level
    }
    if (item['raid_pokemon_id'] != null && item.raid_end > Date.now()) {
        return '<div style="position:relative;">' +
            '<img src="static/forts/' + Store.get('gymMarkerStyle') + '/' + teamStr + '.png" style="width:55px;height:auto;"/>' +
            '<i class="pokemon-raid-sprite n' + item.raid_pokemon_id + '"></i>' +
            '</div>'
    } else if (item['raid_level'] !== null && item.raid_end > Date.now()) {
        var raidEgg = ''
        if (item['raid_level'] <= 2) {
            raidEgg = 'normal'
        } else if (item['raid_level'] <= 4) {
            raidEgg = 'rare'
        } else {
            raidEgg = 'legendary'
        }
        return '<div style="position:relative;">' +
            '<img src="static/forts/' + Store.get('gymMarkerStyle') + '/' + teamStr + '.png" style="width:55px;height:auto;"/>' +
            '<img src="static/raids/egg_' + raidEgg + '.png" style="width:30px;height:auto;position:absolute;top:8px;right:12px;"/>' +
            '</div>'
    } else {
        return '<div>' +
            '<img src="static/forts/' + Store.get('gymMarkerStyle') + '/' + gymTypes[item['team_id']] + '.png" style="width:48px;height: auto;"/>' +
            '</div>'
    }
}

function setupGymMarker(item) {
    var marker = new RichMarker({
        position: new google.maps.LatLng(item['latitude'], item['longitude']),
        map: map,
        content: getGymMarkerIcon(item),
        flat: true,
        anchor: RichMarkerPosition.MIDDLE
    })

    if (!marker.rangeCircle && isRangeActive(map)) {
        marker.rangeCircle = addRangeCircle(marker, map, 'gym', item['team_id'])
    }

    marker.infoWindow = new google.maps.InfoWindow({
        content: gymLabel(item),
        disableAutoPan: true,
        pixelOffset: new google.maps.Size(0, -20)
    })

    var raidLevel = item.raid_level
    if (raidLevel >= Store.get('remember_raid_notify') && item.raid_end > Date.now() && Store.get('remember_raid_notify') !== 0) {
        var title = 'Raid level: ' + raidLevel

        var raidStartStr = getTimeStr(item['raid_start'])
        var raidEndStr = getTimeStr(item['raid_end'])
        var text = raidStartStr + ' - ' + raidEndStr

        var raidStarted = item['raid_pokemon_id'] != null
        var icon
        if (raidStarted) {
            icon = 'static/icons/' + item.raid_pokemon_id + '.png'
            checkAndCreateSound(item.raid_pokemon_id)
        } else {
            var raidEgg = ''
            if (item['raid_level'] <= 2) {
                raidEgg = 'normal'
            } else if (item['raid_level'] <= 4) {
                raidEgg = 'rare'
            } else {
                raidEgg = 'legendary'
            }
            icon = 'static/raids/egg_' + raidEgg + '.png'
            checkAndCreateSound()
        }
        sendNotification(title, text, icon, item['latitude'], item['longitude'])
    }

    if (Store.get('useGymSidebar')) {
        marker.addListener('click', function () {
            var gymSidebar = document.querySelector('#gym-details')
            if (gymSidebar.getAttribute('data-id') === item['gym_id'] && gymSidebar.classList.contains('visible')) {
                gymSidebar.classList.remove('visible')
            } else {
                gymSidebar.setAttribute('data-id', item['gym_id'])
                showGymDetails(item['gym_id'])
            }
        })

        google.maps.event.addListener(marker.infoWindow, 'closeclick', function () {
            marker.persist = null
        })

        if (!isMobileDevice() && !isTouchDevice()) {
            marker.addListener('mouseover', function () {
                marker.infoWindow.open(map, marker)
                clearSelection()
                updateLabelDiffTime()
            })
        }

        marker.addListener('mouseout', function () {
            if (!marker.persist) {
                marker.infoWindow.close()
            }
        })
    } else {
        addListeners(marker)
    }
    return marker
}

function updateGymMarker(item, marker) {
    marker.setContent(getGymMarkerIcon(item))
    marker.infoWindow.setContent(gymLabel(item))

    var raidLevel = item.raid_level
    if (raidLevel >= Store.get('remember_raid_notify') && item.raid_end > Date.now() && Store.get('remember_raid_notify') !== 0) {
        var raidPokemon = mapData.gyms[item['gym_id']].raid_pokemon_id
        if (item.raid_pokemon_id !== raidPokemon) {

            var title = 'Raid level: ' + raidLevel

            var raidStartStr = getTimeStr(item['raid_start'])
            var raidEndStr = getTimeStr(item['raid_end'])
            var text = raidStartStr + ' - ' + raidEndStr

            var raidStarted = item['raid_pokemon_id'] != null
            var icon
            if (raidStarted) {
                icon = 'static/icons/' + item.raid_pokemon_id + '.png'
                checkAndCreateSound(item.raid_pokemon_id)
            } else {
                checkAndCreateSound()
                var raidEgg = ''
                if (item['raid_level'] <= 2) {
                    raidEgg = 'normal'
                } else if (item['raid_level'] <= 4) {
                    raidEgg = 'rare'
                } else {
                    raidEgg = 'legendary'
                }
                icon = 'static/raids/egg_' + raidEgg + '.png'
            }
            sendNotification(title, text, icon, item['latitude'], item['longitude'])
        }
    }

    return marker
}

function updateGymIcons() {
    $.each(mapData.gyms, function (key, value) {
        mapData.gyms[key]['marker'].setContent(getGymMarkerIcon(mapData.gyms[key]))
    })
}

function setupPokestopMarker(item) {
    var imagename = item['lure_expiration'] ? 'PstopLured' : 'Pstop'
    var marker = new google.maps.Marker({
        position: {
            lat: item['latitude'],
            lng: item['longitude']
        },
        map: map,
        zIndex: 2,
        icon: 'static/forts/' + imagename + '.png'
    })

    if (!marker.rangeCircle && isRangeActive(map)) {
        marker.rangeCircle = addRangeCircle(marker, map, 'pokestop')
    }

    marker.infoWindow = new google.maps.InfoWindow({
        content: pokestopLabel(item['lure_expiration'], item['latitude'], item['longitude']),
        disableAutoPan: true
    })

    addListeners(marker)
    return marker
}

function getColorByDate(value) {
    // Changes the color from red to green over 15 mins
    var diff = (Date.now() - value) / 1000 / 60 / 15

    if (diff > 1) {
        diff = 1
    }

    // value from 0 to 1 - Green to Red
    var hue = ((1 - diff) * 120).toString(10)
    return ['hsl(', hue, ',100%,50%)'].join('')
}

function setupScannedMarker(item) {
    var circleCenter = new google.maps.LatLng(item['latitude'], item['longitude'])

    var marker = new google.maps.Circle({
        map: map,
        clickable: false,
        center: circleCenter,
        radius: 70, // metres
        fillColor: getColorByDate(item['last_modified']),
        fillOpacity: 0.1,
        strokeWeight: 1,
        strokeOpacity: 0.5
    })

    return marker
}

function getColorBySpawnTime(value) {
    var now = new Date()
    var seconds = now.getMinutes() * 60 + now.getSeconds()

    // account for hour roll-over
    if (seconds < 900 && value > 2700) {
        seconds += 3600
    } else if (seconds > 2700 && value < 900) {
        value += 3600
    }

    var diff = seconds - value
    var hue = 275 // light purple when spawn is neither about to spawn nor active
    if (diff >= 0 && diff <= 900) {
        // green to red over 15 minutes of active spawn
        hue = (1 - diff / 60 / 15) * 120
    } else if (diff < 0 && diff > -300) {
        // light blue to dark blue over 5 minutes til spawn
        hue = (1 - -diff / 60 / 5) * 50 + 200
    }

    hue = Math.round(hue / 5) * 5

    return hue
}

function changeSpawnIcon(color, zoom) {
    var urlColor = ''
    if (color === 275) {
        urlColor = './static/icons/hsl-275-light.png'
    } else {
        urlColor = './static/icons/hsl-' + color + '.png'
    }
    var zoomScale = 1.6 // adjust this value to change the size of the spawnpoint icons
    var minimumSize = 1
    var newSize = Math.round(zoomScale * (zoom - 10) // this scales the icon based on zoom
    )
    if (newSize < minimumSize) {
        newSize = minimumSize
    }

    var newIcon = {
        url: urlColor,
        scaledSize: new google.maps.Size(newSize, newSize),
        anchor: new google.maps.Point(newSize / 2, newSize / 2)
    }

    return newIcon
}

function spawnPointIndex(color) {
    var newIndex = 1
    var scale = 0
    if (color >= 0 && color <= 120) {
        // high to low over 15 minutes of active spawn
        scale = color / 120
        newIndex = 100 + scale * 100
    } else if (color >= 200 && color <= 250) {
        // low to high over 5 minutes til spawn
        scale = (color - 200) / 50
        newIndex = scale * 100
    }

    return newIndex
}

function setupSpawnpointMarker(item) {
    var circleCenter = new google.maps.LatLng(item['latitude'], item['longitude'])
    var hue = getColorBySpawnTime(item.time)
    var zoom = map.getZoom()

    var marker = new google.maps.Marker({
        map: map,
        position: circleCenter,
        icon: changeSpawnIcon(hue, zoom),
        zIndex: spawnPointIndex(hue)
    })

    marker.infoWindow = new google.maps.InfoWindow({
        content: spawnpointLabel(item),
        disableAutoPan: true,
        position: circleCenter
    })

    addListeners(marker)

    return marker
}

function clearSelection() {
    if (document.selection) {
        document.selection.empty()
    } else if (window.getSelection) {
        window.getSelection().removeAllRanges()
    }
}

function addListeners(marker) {
    marker.addListener('click', function () {
        if (!marker.infoWindowIsOpen) {
            marker.infoWindow.open(map, marker)
            clearSelection()
            updateLabelDiffTime()
            marker.persist = true
            marker.infoWindowIsOpen = true
        } else {
            marker.persist = null
            marker.infoWindow.close()
            marker.infoWindowIsOpen = false
        }
    })

    google.maps.event.addListener(marker.infoWindow, 'closeclick', function () {
        marker.persist = null
    })

    if (!isMobileDevice() && !isTouchDevice()) {
        marker.addListener('mouseover', function () {
            marker.infoWindow.open(map, marker)
            clearSelection()
            updateLabelDiffTime()
        })
    }

    marker.addListener('mouseout', function () {
        if (!marker.persist) {
            marker.infoWindow.close()
        }
    })

    return marker
}

function clearStaleMarkers() {
    $.each(mapData.pokemons, function (key, value) {
        if (mapData.pokemons[key]['disappear_time'] < new Date().getTime() || excludedPokemon.indexOf(mapData.pokemons[key]['pokemon_id']) >= 0 || isTemporaryHidden(mapData.pokemons[key]['pokemon_id'])) {
            if (mapData.pokemons[key].marker.rangeCircle) {
                mapData.pokemons[key].marker.rangeCircle.setMap(null)
                delete mapData.pokemons[key].marker.rangeCircle
            }
            mapData.pokemons[key].marker.setMap(null)
            delete mapData.pokemons[key]
        }
    })

    $.each(mapData.lurePokemons, function (key, value) {
        if (mapData.lurePokemons[key]['lure_expiration'] < new Date().getTime() || excludedPokemon.indexOf(mapData.lurePokemons[key]['pokemon_id']) >= 0) {
            mapData.lurePokemons[key].marker.setMap(null)
            delete mapData.lurePokemons[key]
        }
    })

    $.each(mapData.scanned, function (key, value) {
        // If older than 15mins remove
        if (mapData.scanned[key]['last_modified'] < new Date().getTime() - 15 * 60 * 1000) {
            mapData.scanned[key].marker.setMap(null)
            delete mapData.scanned[key]
        }
    })
}

function showInBoundsMarkers(markers, type) {
    $.each(markers, function (key, value) {
        var marker = markers[key].marker
        var show = false
        if (!markers[key].hidden) {
            if (typeof marker.getBounds === 'function') {
                if (map.getBounds().intersects(marker.getBounds())) {
                    show = true
                }
            } else if (typeof marker.getPosition === 'function') {
                if (map.getBounds().contains(marker.getPosition())) {
                    show = true
                }
            }
        }
        // marker has an associated range
        if (show && rangeMarkers.indexOf(type) !== -1) {
            // no range circle yet...let's create one
            if (!marker.rangeCircle) {
                // but only if range is active
                if (isRangeActive(map)) {
                    if (type === 'gym') marker.rangeCircle = addRangeCircle(marker, map, type, markers[key].team_id)
                    else marker.rangeCircle = addRangeCircle(marker, map, type)
                }
            } else {
                // there's already a range circle
                if (isRangeActive(map)) {
                    marker.rangeCircle.setMap(map)
                } else {
                    marker.rangeCircle.setMap(null)
                }
            }
        }

        if (show && !marker.getMap()) {
            marker.setMap(map
                // Not all markers can be animated (ex: scan locations)
            )
            if (marker.setAnimation && marker.oldAnimation) {
                marker.setAnimation(marker.oldAnimation)
            }
        } else if (!show && marker.getMap()) {
            // Not all markers can be animated (ex: scan locations)
            if (marker.getAnimation) {
                marker.oldAnimation = marker.getAnimation()
            }
            if (marker.rangeCircle) marker.rangeCircle.setMap(null)
            marker.setMap(null)
        }
    })
}

function loadRawData() {
    var loadPokemon = Store.get('showPokemon')
    var loadGyms = (Store.get('showGyms') || Store.get('showRaids')) ? 'true' : 'false'
    var loadPokestops = Store.get('showPokestops')
    var loadScanned = Store.get('showScanned')
    var loadSpawnpoints = Store.get('showSpawnpoints')
    var loadLuredOnly = Boolean(Store.get('showLuredPokestopsOnly'))

    var bounds = map.getBounds()
    var swPoint = bounds.getSouthWest()
    var nePoint = bounds.getNorthEast()
    var swLat = swPoint.lat()
    var swLng = swPoint.lng()
    var neLat = nePoint.lat()
    var neLng = nePoint.lng()

    return $.ajax({
        url: 'raw_data',
        type: 'GET',
        timeout: 300000,
        data: {
            'timestamp': timestamp,
            'pokemon': loadPokemon,
            'lastpokemon': lastpokemon,
            'pokestops': loadPokestops,
            'lastpokestops': lastpokestops,
            'luredonly': loadLuredOnly,
            'gyms': loadGyms,
            'lastgyms': lastgyms,
            'scanned': loadScanned,
            'lastslocs': lastslocs,
            'spawnpoints': loadSpawnpoints,
            'lastspawns': lastspawns,
            'swLat': swLat,
            'swLng': swLng,
            'neLat': neLat,
            'neLng': neLng,
            'oSwLat': oSwLat,
            'oSwLng': oSwLng,
            'oNeLat': oNeLat,
            'oNeLng': oNeLng,
            'reids': String(reincludedPokemon),
            'eids': String(excludedPokemon)
        },
        dataType: 'json',
        cache: false,
        beforeSend: function beforeSend() {
            if (rawDataIsLoading) {
                return false
            } else {
                rawDataIsLoading = true
            }
        },
        error: function error() {
            // Display error toast
            toastr['error']('Please check connectivity or reduce marker settings.', 'Error getting data')
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': false,
                'positionClass': 'toast-top-right',
                'preventDuplicates': true,
                'onclick': null,
                'showDuration': '300',
                'hideDuration': '1000',
                'timeOut': '25000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut'
            }
        },
        complete: function complete() {
            rawDataIsLoading = false
        }
    })
}

function processPokemons(i, item) {
    if (!Store.get('showPokemon')) {
        return false // in case the checkbox was unchecked in the meantime.
    }

    if (!(item['encounter_id'] in mapData.pokemons) && excludedPokemon.indexOf(item['pokemon_id']) < 0 && item['disappear_time'] > Date.now() && !isTemporaryHidden(item['pokemon_id'])) {
        // add marker to map and item to dict
        if (item.marker) {
            item.marker.setMap(null)
        }
        if (!item.hidden) {
            item.marker = setupPokemonMarker(item, map)
            customizePokemonMarker(item.marker, item)
            mapData.pokemons[item['encounter_id']] = item
        }
    }
}

function processPokestops(i, item) {
    if (!Store.get('showPokestops')) {
        return false
    }

    if (Store.get('showLuredPokestopsOnly') && !item['lure_expiration']) {
        return true
    }

    if (!mapData.pokestops[item['pokestop_id']]) {
        // new pokestop, add marker to map and item to dict
        if (item.marker && item.marker.rangeCircle) {
            item.marker.rangeCircle.setMap(null)
        }
        if (item.marker) {
            item.marker.setMap(null)
        }
        item.marker = setupPokestopMarker(item)
        mapData.pokestops[item['pokestop_id']] = item
    } else {
        // change existing pokestop marker to unlured/lured
        var item2 = mapData.pokestops[item['pokestop_id']]
        if (!!item['lure_expiration'] !== !!item2['lure_expiration']) {
            if (item2.marker && item2.marker.rangeCircle) {
                item2.marker.rangeCircle.setMap(null)
            }
            item2.marker.setMap(null)
            item.marker = setupPokestopMarker(item)
            mapData.pokestops[item['pokestop_id']] = item
        }
    }
}

function updatePokestops() {
    if (!Store.get('showPokestops')) {
        return false
    }

    var removeStops = []
    var currentTime = new Date().getTime()

    // change lured pokestop marker to unlured when expired
    $.each(mapData.pokestops, function (key, value) {
        if (value['lure_expiration'] && value['lure_expiration'] < currentTime) {
            value['lure_expiration'] = null
            if (value.marker && value.marker.rangeCircle) {
                value.marker.rangeCircle.setMap(null)
            }
            value.marker.setMap(null)
            value.marker = setupPokestopMarker(value)
        }
    })

    // remove unlured stops if show lured only is selected
    if (Store.get('showLuredPokestopsOnly')) {
        $.each(mapData.pokestops, function (key, value) {
            if (!value['lure_expiration']) {
                removeStops.push(key)
            }
        })
        $.each(removeStops, function (key, value) {
            if (mapData.pokestops[value] && mapData.pokestops[value].marker) {
                if (mapData.pokestops[value].marker.rangeCircle) {
                    mapData.pokestops[value].marker.rangeCircle.setMap(null)
                }
                mapData.pokestops[value].marker.setMap(null)
                delete mapData.pokestops[value]
            }
        })
    }
}

function processGyms(i, item) {
    if (!Store.get('showGyms') && !Store.get('showRaids')) {
        return false // in case the checkbox was unchecked in the meantime.
    }

    var gymLevel = item.slots_available
    var raidLevel = item.raid_level
    var removeGymFromMap = function removeGymFromMap(gymid) {
        if (mapData.gyms[gymid] && mapData.gyms[gymid].marker) {
            if (mapData.gyms[gymid].marker.rangeCircle) {
                mapData.gyms[gymid].marker.rangeCircle.setMap(null)
            }
            mapData.gyms[gymid].marker.setMap(null)
            delete mapData.gyms[gymid]
        }
    }

    if (!Store.get('showGyms') && Store.get('showRaids')) {
        if (item.raid_end === undefined) {
            removeGymFromMap(item['gym_id'])
            return true
        }
    }

    if (!Store.get('showGyms') && Store.get('showRaids')) {
        if (item.raid_end < Date.now()) {
            removeGymFromMap(item['gym_id'])
            return true
        }
    }

    if (Store.get('showGyms') && !Store.get('showRaids')) {
        if (item.raid_end > Date.now()) {
            removeGymFromMap(item['gym_id'])
            return true
        }
    }

    if (Store.get('activeRaids') && item.raid_end > Date.now()) {
        if (item.raid_pokemon_id === undefined) {
            removeGymFromMap(item['gym_id'])
            return true
        }
    }

    if (raidLevel < Store.get('minRaidLevel') && item.raid_end > Date.now()) {
        removeGymFromMap(item['gym_id'])
        return true
    }

    if (raidLevel > Store.get('maxRaidLevel') && item.raid_end > Date.now()) {
        removeGymFromMap(item['gym_id'])
        return true
    }

    if (Store.get('showOpenGymsOnly')) {
        if (item.slots_available === 0 && (item.raid_end === undefined || item.raid_end < Date.now())) {
            removeGymFromMap(item['gym_id'])
            return true
        }
    }

    if (Store.get('showTeamGymsOnly') && Store.get('showTeamGymsOnly') !== item.team_id && (item.raid_end === undefined || item.raid_end < Date.now())) {
        removeGymFromMap(item['gym_id'])
        return true
    }

    if (Store.get('showLastUpdatedGymsOnly')) {
        var now = new Date()
        if (item.last_scanned == null) {
            if (Store.get('showLastUpdatedGymsOnly') * 3600 * 1000 + item.last_modified < now.getTime() && (item.raid_end === undefined || item.raid_end < Date.now())) {
                removeGymFromMap(item['gym_id'])
                return true
            }
        } else {
            if (Store.get('showLastUpdatedGymsOnly') * 3600 * 1000 + item.last_scanned < now.getTime() && (item.raid_end === undefined || item.raid_end < Date.now())) {
                removeGymFromMap(item['gym_id'])
                return true
            }
        }
    }

    if (gymLevel < Store.get('minGymLevel') && (item.raid_end === undefined || item.raid_end < Date.now())) {
        removeGymFromMap(item['gym_id'])
        return true
    }

    if (gymLevel > Store.get('maxGymLevel') && (item.raid_end === undefined || item.raid_end < Date.now())) {
        removeGymFromMap(item['gym_id'])
        return true
    }

    if (item['gym_id'] in mapData.gyms) {
        item.marker = updateGymMarker(item, mapData.gyms[item['gym_id']].marker)
    } else {
        // add marker to map and item to dict
        item.marker = setupGymMarker(item)
    }
    mapData.gyms[item['gym_id']] = item
}

function processScanned(i, item) {
    if (!Store.get('showScanned')) {
        return false
    }

    var scanId = item['latitude'] + '|' + item['longitude']

    if (!(scanId in mapData.scanned)) {
        // add marker to map and item to dict
        if (item.marker) {
            item.marker.setMap(null)
        }
        item.marker = setupScannedMarker(item)
        mapData.scanned[scanId] = item
    } else {
        mapData.scanned[scanId].last_modified = item['last_modified']
    }
}

function updateScanned() {
    if (!Store.get('showScanned')) {
        return false
    }

    $.each(mapData.scanned, function (key, value) {
        if (map.getBounds().intersects(value.marker.getBounds())) {
            value.marker.setOptions({
                fillColor: getColorByDate(value['last_modified'])
            })
        }
    })
}

function processSpawnpoints(i, item) {
    if (!Store.get('showSpawnpoints')) {
        return false
    }

    var id = item['spawnpoint_id']

    if (!(id in mapData.spawnpoints)) {
        // add marker to map and item to dict
        if (item.marker) {
            item.marker.setMap(null)
        }
        item.marker = setupSpawnpointMarker(item)
        mapData.spawnpoints[id] = item
    }
}

function updateSpawnPoints() {
    if (!Store.get('showSpawnpoints')) {
        return false
    }

    var zoom = map.getZoom()

    $.each(mapData.spawnpoints, function (key, value) {
        if (map.getBounds().contains(value.marker.getPosition())) {
            var hue = getColorBySpawnTime(value['time'])
            value.marker.setIcon(changeSpawnIcon(hue, zoom))
            value.marker.setZIndex(spawnPointIndex(hue))
        }
    })
}

function updateMap() {
    var position = map.getCenter()
    Store.set('startAtLastLocationPosition', {
        lat: position.lat(),
        lng: position.lng()
    })

    loadRawData().done(function (result) {
        $.each(result.pokemons, processPokemons)
        $.each(result.pokestops, processPokestops)
        $.each(result.gyms, processGyms)
        $.each(result.scanned, processScanned)
        $.each(result.spawnpoints, processSpawnpoints)
        showInBoundsMarkers(mapData.pokemons, 'pokemon')
        showInBoundsMarkers(mapData.lurePokemons, 'pokemon')
        showInBoundsMarkers(mapData.gyms, 'gym')
        showInBoundsMarkers(mapData.pokestops, 'pokestop')
        showInBoundsMarkers(mapData.scanned, 'scanned')
        showInBoundsMarkers(mapData.spawnpoints, 'inbound'
            //      drawScanPath(result.scanned)
        )
        clearStaleMarkers()

        updateScanned()
        updateSpawnPoints()
        updatePokestops()

        if ($('#stats').hasClass('visible')) {
            countMarkers(map)
        }

        oSwLat = result.oSwLat
        oSwLng = result.oSwLng
        oNeLat = result.oNeLat
        oNeLng = result.oNeLng

        lastgyms = result.lastgyms
        lastpokestops = result.lastpokestops
        lastpokemon = result.lastpokemon
        lastslocs = result.lastslocs
        lastspawns = result.lastspawns

        reids = result.reids
        if (reids instanceof Array) {
            reincludedPokemon = reids.filter(function (e) {
                return this.indexOf(e) < 0
            }, reincludedPokemon)
        }
        timestamp = result.timestamp
        lastUpdateTime = Date.now()
    })
}

function drawScanPath(points) { // eslint-disable-line no-unused-vars
    var scanPathPoints = []
    $.each(points, function (idx, point) {
        scanPathPoints.push({
            lat: point['latitude'],
            lng: point['longitude']
        })
    })
    if (scanPath) {
        scanPath.setMap(null)
    }
    scanPath = new google.maps.Polyline({
        path: scanPathPoints,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2,
        map: map
    })
}

function redrawPokemon(pokemonList) {
    var skipNotification = true
    $.each(pokemonList, function (key, value) {
        var item = pokemonList[key]
        if (!item.hidden) {
            if (item.marker.rangeCircle) item.marker.rangeCircle.setMap(null)
            var newMarker = setupPokemonMarker(item, map, this.marker.animationDisabled)
            customizePokemonMarker(newMarker, item, skipNotification)
            item.marker.setMap(null)
            pokemonList[key].marker = newMarker
        }
    })
}

var updateLabelDiffTime = function updateLabelDiffTime() {
    $('.label-countdown').each(function (index, element) {
        var disappearsAt = getTimeUntil(parseInt(element.getAttribute('disappears-at')))

        var hours = disappearsAt.hour
        var minutes = disappearsAt.min
        var seconds = disappearsAt.sec
        var timestring = ''

        if (disappearsAt.ttime < disappearsAt.now) {
            if (element.hasAttribute('start')) {
                timestring = '(started)'
            } else if (element.hasAttribute('end')) {
                timestring = '(ended)'
            } else {
                timestring = '(expired)'
            }
        } else {
            timestring = '('
            if (hours > 0) {
                timestring += hours + 'h'
            }

            timestring += lpad(minutes, 2, 0) + 'm'
            timestring += lpad(seconds, 2, 0) + 's'
            timestring += ')'
        }

        $(element).text(timestring)
    })
}

function getPointDistance(pointA, pointB) {
    return google.maps.geometry.spherical.computeDistanceBetween(pointA, pointB)
}

function sendNotification(title, text, icon, lat, lng) {
    if (!('Notification' in window)) {
        return false // Notifications are not present in browser
    }

    if (Push.Permission.has()) {
        Push.create(title, {
            icon: icon,
            body: text,
            vibrate: 1000,
            onClick: function () {
                window.focus()
                this.close()
                centerMap(lat, lng, 20)
            }
        })
    }
}

function createMyLocationButton() {
    var locationContainer = document.createElement('div')

    var locationButton = document.createElement('button')
    locationButton.style.backgroundColor = '#fff'
    locationButton.style.border = 'none'
    locationButton.style.outline = 'none'
    locationButton.style.width = '28px'
    locationButton.style.height = '28px'
    locationButton.style.borderRadius = '2px'
    locationButton.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)'
    locationButton.style.cursor = 'pointer'
    locationButton.style.marginRight = '10px'
    locationButton.style.padding = '0px'
    locationButton.title = 'My Location'
    locationContainer.appendChild(locationButton)

    var locationIcon = document.createElement('div')
    locationIcon.style.margin = '5px'
    locationIcon.style.width = '18px'
    locationIcon.style.height = '18px'
    locationIcon.style.backgroundImage = 'url(static/mylocation-sprite-1x.png)'
    locationIcon.style.backgroundSize = '180px 18px'
    locationIcon.style.backgroundPosition = '0px 0px'
    locationIcon.style.backgroundRepeat = 'no-repeat'
    locationIcon.id = 'current-location'
    locationButton.appendChild(locationIcon)

    locationButton.addEventListener('click', function () {
        centerMapOnLocation()
    })

    locationContainer.index = 1
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(locationContainer)

    google.maps.event.addListener(map, 'dragend', function () {
        var currentLocation = document.getElementById('current-location')
        currentLocation.style.backgroundPosition = '0px 0px'
    })
}

function centerMapOnLocation() {
    var currentLocation = document.getElementById('current-location')
    if (currentLocation !== null) {
        var imgX = '0'
        var animationInterval = setInterval(function () {
            if (imgX === '-18') {
                imgX = '0'
            } else {
                imgX = '-18'
            }
            currentLocation.style.backgroundPosition = imgX + 'px 0'
        }, 500)
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
            locationMarker.setPosition(latlng)
            map.setCenter(latlng)
            Store.set('followMyLocationPosition', {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            })
            clearInterval(animationInterval)
            if (currentLocation !== null) {
                currentLocation.style.backgroundPosition = '-144px 0px'
            }
        })
    } else {
        clearInterval(animationInterval)
        if (currentLocation !== null) {
            currentLocation.style.backgroundPosition = '0px 0px'
        }
    }
}

function changeLocation(lat, lng) {
    var loc = new google.maps.LatLng(lat, lng)
    map.setCenter(loc)
}

function centerMap(lat, lng, zoom) {
    var loc = new google.maps.LatLng(lat, lng)

    map.setCenter(loc)

    if (zoom) {
        storeZoom = false
        map.setZoom(zoom)
    }
}

function i8ln(word) {
    if ($.isEmptyObject(i8lnDictionary) && language !== 'en' && languageLookups < languageLookupThreshold) {
        $.ajax({
            url: 'static/dist/locales/' + language + '.min.json',
            dataType: 'json',
            async: false,
            success: function success(data) {
                i8lnDictionary = data
            },
            error: function error(jqXHR, status, _error) {
                console.log('Error loading i8ln dictionary: ' + _error)
                languageLookups++
            }
        })
    }
    if (word in i8lnDictionary) {
        return i8lnDictionary[word]
    } else {
        // Word doesn't exist in dictionary return it as is
        return word
    }
}

function updateGeoLocation() {
    if (navigator.geolocation && Store.get('followMyLocation')) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude
            var lng = position.coords.longitude
            var center = new google.maps.LatLng(lat, lng)

            if (Store.get('followMyLocation')) {
                if (typeof locationMarker !== 'undefined' && getPointDistance(locationMarker.getPosition(), center) >= 5) {
                    map.panTo(center)
                    locationMarker.setPosition(center)
                    if (Store.get('spawnArea')) {
                        if (locationMarker.rangeCircle) {
                            locationMarker.rangeCircle.setMap(null)
                            delete locationMarker.rangeCircle
                        }
                        var rangeCircleOpts = {
                            map: map,
                            radius: 35, // meters
                            strokeWeight: 1,
                            strokeColor: '#FF9200',
                            strokeOpacity: 0.9,
                            center: center,
                            fillColor: '#FF9200',
                            fillOpacity: 0.3
                        }
                        locationMarker.rangeCircle = new google.maps.Circle(rangeCircleOpts)
                    }
                    Store.set('followMyLocationPosition', {
                        lat: lat,
                        lng: lng
                    })
                }
            }
        })
    }
}

function createUpdateWorker() {
    try {
        if (isMobileDevice() && window.Worker) {
            var updateBlob = new Blob(["onmessage = function(e) {\n                var data = e.data\n                if (data.name === 'backgroundUpdate') {\n                    self.setInterval(function () {self.postMessage({name: 'backgroundUpdate'})}, 5000)\n                }\n            }"])

            var updateBlobURL = window.URL.createObjectURL(updateBlob)

            updateWorker = new Worker(updateBlobURL)

            updateWorker.onmessage = function (e) {
                var data = e.data
                if (document.hidden && data.name === 'backgroundUpdate' && Date.now() - lastUpdateTime > 2500) {
                    updateMap()
                    updateGeoLocation()
                }
            }

            updateWorker.postMessage({
                name: 'backgroundUpdate'
            })
        }
    } catch (ex) {
        console.log('Webworker error: ' + ex.message)
    }
}

function showGymDetails(id) { // eslint-disable-line no-unused-vars
    var sidebar = document.querySelector('#gym-details')
    var sidebarClose

    sidebar.classList.add('visible')

    var data = $.ajax({
        url: 'gym_data',
        type: 'GET',
        timeout: 300000,
        data: {
            'id': id
        },
        dataType: 'json',
        cache: false
    })

    data.done(function (result) {
        var lastModifiedStr = getDateStr(result.last_modified)
        var lastScannedStr = ''
        if (result.last_scanned != null) {
            lastScannedStr =
                '<div style="font-size: .7em">' +
                'Last Scanned: ' + getDateStr(result.last_scanned) +
                '</div>'
        }
        var pokemon = result.pokemon !== undefined ? result.pokemon : []
        var freeSlots = result.slots_available
        var gymLevelStr = ''
        if (result.team_id !== 0) {
            gymLevelStr =
                '<center class="team-' + result.team_id + '-text">' +
                '<b class="team-' + result.team_id + '-text">' + freeSlots + ' Free Slots</b>' +
                '</center>'
        }

        var raidSpawned = result['raid_level'] != null
        var raidStarted = result['raid_pokemon_id'] != null

        var raidStr = ''
        var raidIcon = ''
        if (raidSpawned && result.raid_end > Date.now()) {
            var levelStr = ''
            for (var i = 0; i < result['raid_level']; i++) {
                levelStr += '★'
            }
            raidStr = '<h3 style="margin-bottom: 0">Raid ' + levelStr
            if (raidStarted) {
                var cpStr = ''
                if (result.raid_pokemon_cp != null) {
                    cpStr = ' CP ' + result.raid_pokemon_cp
                }
                raidStr += '<br>' + result.raid_pokemon_name + cpStr
            }
            raidStr += '</h3>'
            if (raidStarted && result.raid_pokemon_move_1 != null && result.raid_pokemon_move_2 != null) {
                var pMove1 = (moves[result['raid_pokemon_move_1']] !== undefined) ? i8ln(moves[result['raid_pokemon_move_1']]['name']) : 'gen/unknown'
                var pMove2 = (moves[result['raid_pokemon_move_2']] !== undefined) ? i8ln(moves[result['raid_pokemon_move_2']]['name']) : 'gen/unknown'
                raidStr += '<div><b>' + pMove1 + ' / ' + pMove2 + '</b></div>'
            }

            var raidStartStr = getTimeStr(result['raid_start'])
            var raidEndStr = getTimeStr(result['raid_end'])
            raidStr += '<div>Start: <b>' + raidStartStr + '</b> <span class="label-countdown" disappears-at="' + result['raid_start'] + '" start>(00m00s)</span></div>'
            raidStr += '<div>End: <b>' + raidEndStr + '</b> <span class="label-countdown" disappears-at="' + result['raid_end'] + '" end>(00m00s)</span></div>'

            if (raidStarted) {
                raidIcon = '<i class="pokemon-large-raid-sprite n' + result.raid_pokemon_id + '"></i>'
            } else {
                var raidEgg = ''
                if (result['raid_level'] <= 2) {
                    raidEgg = 'normal'
                } else if (result['raid_level'] <= 4) {
                    raidEgg = 'rare'
                } else {
                    raidEgg = 'legendary'
                }
                raidIcon = '<img src="static/raids/egg_' + raidEgg + '.png">'
            }
        }

        var pokemonHtml = ''

        var headerHtml =
            '<center class="team-' + result.team_id + '-text">' +
            '<div>' +
            '<b class="team-' + result.team_id + '-text">' + (result.name || '') + '</b>' +
            '</div>' +
            '<div>' +
            '<img height="100px" style="padding: 5px;" src="static/forts/' + gymTypes[result.team_id] + '_large.png">' +
            raidIcon +
            '</div>' +
            raidStr +
            gymLevelStr +
            '<div style="font-size: .7em">' +
            'Last Modified: ' + lastModifiedStr +
            '</div>' +
            lastScannedStr +
            '<div>' +
            '<a href=\'javascript:void(0)\' onclick=\'javascript:openMapDirections(' + result.latitude + ',' + result.longitude + ')\' title=\'View in Maps\'>Get directions</a>' +
            '</div>' +
            '</center>'

        if (pokemon.length) {
            $.each(pokemon, function (i, pokemon) {
                var perfectPercent = getIv(pokemon.iv_attack, pokemon.iv_defense, pokemon.iv_stamina)
                var moveEnergy = Math.round(100 / pokemon.move_2_energy)

                pokemonHtml +=
                    '<tr onclick=toggleGymPokemonDetails(this)>' +
                    '<td width="30px">' +
                    '<i class="pokemon-sprite n' + pokemon.pokemon_id + '"></i>' +
                    '</td>' +
                    '<td class="team-' + result.team_id + '-text">' +
                    '<div style="line-height:1em">' + pokemon.pokemon_name + '</div>' +
                    '<div class="cp">CP ' + pokemon.pokemon_cp + '</div>' +
                    '</td>' +
                    '<td width="190" class="team-' + result.team_id + '-text" align="center">' +
                    '<div class="trainer-level">' + pokemon.trainer_level + '</div>' +
                    '<div style="line-height: 1em">' + pokemon.trainer_name + '</div>' +
                    '</td>' +
                    '<td width="10">' +
                    '<!--<a href="#" onclick="toggleGymPokemonDetails(this)">-->' +
                    '<i class="team-' + result.team_id + '-text fa fa-angle-double-down"></i>' +
                    '<!--</a>-->' +
                    '</td>' +
                    '</tr>' +
                    '<tr class="details">' +
                    '<td colspan="2">' +
                    '<div class="ivs">' +
                    '<div class="iv">' +
                    '<div class="type">ATK</div>' +
                    '<div class="value">' +
                    pokemon.iv_attack +
                    '</div>' +
                    '</div>' +
                    '<div class="iv">' +
                    '<div class="type">DEF</div>' +
                    '<div class="value">' +
                    pokemon.iv_defense +
                    '</div>' +
                    '</div>' +
                    '<div class="iv">' +
                    '<div class="type">STA</div>' +
                    '<div class="value">' +
                    pokemon.iv_stamina +
                    '</div>' +
                    '</div>' +
                    '<div class="iv" style="width: 36px">' +
                    '<div class="type">PERFECT</div>' +
                    '<div class="value">' +
                    perfectPercent.toFixed(0) + '' +
                    '<span style="font-size: .6em">%</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '<td colspan="2">' +
                    '<div class="moves">' +
                    '<div class="move">' +
                    '<div class="name">' +
                    pokemon.move_1_name +
                    ' <div class="type ' + pokemon.move_1_type.type_en.toLowerCase() + '">' + pokemon.move_1_type.type + '</div>' +
                    '</div>' +
                    '<div class="damage">' +
                    pokemon.move_1_damage +
                    '</div>' +
                    '</div>' +
                    '<br>' +
                    '<div class="move">' +
                    '<div class="name">' +
                    pokemon.move_2_name +
                    ' <div class="type ' + pokemon.move_2_type.type_en.toLowerCase() + '">' + pokemon.move_2_type.type + '</div>' +
                    '<div>' +
                    '<i class="move-bar-sprite move-bar-sprite-' + moveEnergy + '"></i>' +
                    '</div>' +
                    '</div>' +
                    '<div class="damage">' +
                    pokemon.move_2_damage +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '</tr>'
            })

            pokemonHtml = '<table><tbody>' + pokemonHtml + '</tbody></table>'
        } else if (result.team_id === 0) {
            pokemonHtml = ''
        } else {
            pokemonHtml =
                '<center class="team-' + result.team_id + '-text">' +
                'Gym Leader:<br>' +
                '<i class="pokemon-large-sprite n' + result.guard_pokemon_id + '"></i><br>' +
                '<b class="team-' + result.team_id + '-text">' + result.guard_pokemon_name + '</b>' +
                '<p style="font-size: .75em margin: 5px">' +
                'No additional gym information is available for this gym. Make sure you are collecting detailed gym info. If you have detailed gym info collection running, this gym\'s Pokemon information may be out of date.' +
                '</p>' +
                '</center>'
        }

        sidebar.innerHTML = headerHtml + pokemonHtml

        sidebarClose = document.createElement('a')
        sidebarClose.href = '#'
        sidebarClose.className = 'close'
        sidebarClose.tabIndex = 0
        sidebar.appendChild(sidebarClose)

        sidebarClose.addEventListener('click', function (event) {
            event.preventDefault()
            event.stopPropagation()
            sidebar.classList.remove('visible')
        })
    })
}

function toggleGymPokemonDetails(e) { // eslint-disable-line no-unused-vars
    e.lastElementChild.firstElementChild.classList.toggle('fa-angle-double-up')
    e.lastElementChild.firstElementChild.classList.toggle('fa-angle-double-down')
    e.nextElementSibling.classList.toggle('visible')
}

//
// Page Ready Exection
//

$(function () {
    if (Push.Permission.has()) {
        console.log('Push has notification permission')
        return
    }

    Push.Permission.request()
})

$(function () {
    cries = (function () {
        var cries = null
        $.ajax({
            'global': false,
            'url': 'static/dist/data/cries.min.json',
            'dataType': 'json',
            'success': function (data) {
                cries = data
                createjs.Sound.alternateExtensions = ['mp3']
                // createjs.Sound.on('fileload', loadSound)
                createjs.Sound.registerSounds(cries, assetsPath)
            }
        })
    })()
})

$(function () {
    // populate Navbar Style menu
    $selectStyle = $('#map-style')

    // Load Stylenames, translate entries, and populate lists
    $.getJSON('static/dist/data/mapstyle.min.json').done(function (data) {
        var styleList = []

        $.each(data, function (key, value) {
            styleList.push({
                id: key,
                text: i8ln(value)
            })
        })

        // setup the stylelist
        $selectStyle.select2({
            placeholder: 'Select Style',
            data: styleList,
            minimumResultsForSearch: Infinity
        })

        // setup the list change behavior
        $selectStyle.on('change', function (e) {
            selectedStyle = $selectStyle.val()
            map.setMapTypeId(selectedStyle)
            Store.set('map_style', selectedStyle)
        })

        // recall saved mapstyle
        $selectStyle.val(Store.get('map_style')).trigger('change')
    })

    $selectIconResolution = $('#pokemon-icons')

    $selectIconResolution.select2({
        placeholder: 'Select Icon Resolution',
        minimumResultsForSearch: Infinity
    })

    $selectIconResolution.on('change', function () {
        Store.set('pokemonIcons', this.value)
        redrawPokemon(mapData.pokemons)
        redrawPokemon(mapData.lurePokemons)
    })

    $selectIconSize = $('#pokemon-icon-size')

    $selectIconSize.select2({
        placeholder: 'Select Icon Size',
        minimumResultsForSearch: Infinity
    })

    $selectIconSize.on('change', function () {
        Store.set('iconSizeModifier', this.value)
        redrawPokemon(mapData.pokemons)
        redrawPokemon(mapData.lurePokemons)
    })

    $switchOpenGymsOnly = $('#open-gyms-only-switch')

    $switchOpenGymsOnly.on('change', function () {
        Store.set('showOpenGymsOnly', this.checked)
        lastgyms = false
        updateMap()
    })

    $selectTeamGymsOnly = $('#team-gyms-only-switch')

    $selectTeamGymsOnly.select2({
        placeholder: 'Only Show Gyms For Team',
        minimumResultsForSearch: Infinity
    })

    $selectTeamGymsOnly.on('change', function () {
        Store.set('showTeamGymsOnly', this.value)
        lastgyms = false
        updateMap()
    })

    $selectLastUpdateGymsOnly = $('#last-update-gyms-switch')

    $selectLastUpdateGymsOnly.select2({
        placeholder: 'Only Show Gyms Last Updated',
        minimumResultsForSearch: Infinity
    })

    $selectLastUpdateGymsOnly.on('change', function () {
        Store.set('showLastUpdatedGymsOnly', this.value)
        lastgyms = false
        updateMap()
    })

    $selectMinGymLevel = $('#min-level-gyms-filter-switch')

    $selectMinGymLevel.select2({
        placeholder: 'Minimum Gym Level',
        minimumResultsForSearch: Infinity
    })

    $selectMinGymLevel.on('change', function () {
        Store.set('minGymLevel', this.value)
        lastgyms = false
        updateMap()
    })

    $selectMaxGymLevel = $('#max-level-gyms-filter-switch')

    $selectMaxGymLevel.select2({
        placeholder: 'Maximum Gym Level',
        minimumResultsForSearch: Infinity
    })

    $selectMaxGymLevel.on('change', function () {
        Store.set('maxGymLevel', this.value)
        lastgyms = false
        updateMap()
    })

    $switchActiveRaids = $('#active-raids-switch')

    $switchActiveRaids.on('change', function () {
        Store.set('activeRaids', this.checked)
        updateMap()
    })

    $selectMinRaidLevel = $('#min-level-raids-filter-switch')

    $selectMinRaidLevel.select2({
        placeholder: 'Minimum Raid Level',
        minimumResultsForSearch: Infinity
    })

    $selectMinRaidLevel.on('change', function () {
        Store.set('minRaidLevel', this.value)
        lastgyms = false
        updateMap()
    })

    $selectMaxRaidLevel = $('#max-level-raids-filter-switch')

    $selectMaxRaidLevel.select2({
        placeholder: 'Maximum Raid Level',
        minimumResultsForSearch: Infinity
    })

    $selectMaxRaidLevel.on('change', function () {
        Store.set('maxRaidLevel', this.value)
        lastgyms = false
        updateMap()
    })

    $selectLuredPokestopsOnly = $('#lured-pokestops-only-switch')

    $selectLuredPokestopsOnly.select2({
        placeholder: 'Only Show Lured Pokestops',
        minimumResultsForSearch: Infinity
    })

    $selectLuredPokestopsOnly.on('change', function () {
        Store.set('showLuredPokestopsOnly', this.value)
        lastpokestops = false
        updateMap()
    })
    $switchGymSidebar = $('#gym-sidebar-switch')

    $switchGymSidebar.on('change', function () {
        Store.set('useGymSidebar', this.checked)
        lastgyms = false
        $.each(['gyms'], function (d, dType) {
            $.each(mapData[dType], function (key, value) {
                // for any marker you're turning off, you'll want to wipe off the range
                if (mapData[dType][key].marker.rangeCircle) {
                    mapData[dType][key].marker.rangeCircle.setMap(null)
                    delete mapData[dType][key].marker.rangeCircle
                }
                mapData[dType][key].marker.setMap(null)
            })
            mapData[dType] = {}
        })
        updateMap()
    })

    $selectLocationIconMarker = $('#locationmarker-style')

    $.getJSON('static/dist/data/searchmarkerstyle.min.json').done(function (data) {
        searchMarkerStyles = data
        var searchMarkerStyleList = []

        $.each(data, function (key, value) {
            searchMarkerStyleList.push({
                id: key,
                text: value.name
            })
        })

        locationMarker = createLocationMarker()

        if (Store.get('startAtUserLocation')) {
            centerMapOnLocation()
        }

        if (Store.get('startAtLastLocation')) {
            var position = Store.get('startAtLastLocationPosition')
            var lat = 'lat' in position ? position.lat : centerLat
            var lng = 'lng' in position ? position.lng : centerLng

            var latlng = new google.maps.LatLng(lat, lng)
            locationMarker.setPosition(latlng)
            map.setCenter(latlng)
        }

        $selectLocationIconMarker.select2({
            placeholder: 'Select Location Marker',
            data: searchMarkerStyleList,
            minimumResultsForSearch: Infinity
        })

        $selectLocationIconMarker.on('change', function (e) {
            Store.set('locationMarkerStyle', this.value)
            updateLocationMarker(this.value)
        })

        $selectLocationIconMarker.val(Store.get('locationMarkerStyle')).trigger('change')
    })

    $selectGymMarkerStyle = $('#gym-marker-style')

    $selectGymMarkerStyle.select2({
        placeholder: 'Select Style',
        minimumResultsForSearch: Infinity
    })

    $selectGymMarkerStyle.on('change', function (e) {
        Store.set('gymMarkerStyle', this.value)
        updateGymIcons()
    })

    $selectGymMarkerStyle.val(Store.get('gymMarkerStyle')).trigger('change')
})

$(function () {
    function formatState(state) {
        if (!state.id) {
            return state.text
        }
        var $state = $('<span><i class="pokemon-sprite n' + state.element.value.toString() + '"></i> ' + state.text + '</span>')
        return $state
    }

    $.getJSON('static/dist/data/moves.min.json').done(function (data) {
        moves = data
    })

    $selectExclude = $('#exclude-pokemon')
    $selectPokemonNotify = $('#notify-pokemon')
    $selectRarityNotify = $('#notify-rarity')
    $textPerfectionNotify = $('#notify-perfection')
    $raidNotify = $('#notify-raid')
    var numberOfPokemon = 493

    $raidNotify.select2({
        placeholder: 'Minimum raid level',
        minimumResultsForSearch: Infinity
    })

    $raidNotify.on('change', function () {
        Store.set('remember_raid_notify', this.value)
    })

    // Load pokemon names and populate lists
    $.getJSON('static/dist/data/pokemon.min.json').done(function (data) {
        var pokeList = []

        $.each(data, function (key, value) {
            if (key > numberOfPokemon) {
                return false
            }
            var _types = []
            pokeList.push({
                id: key,
                text: i8ln(value['name']) + ' - #' + key
            })
            value['name'] = i8ln(value['name'])
            value['rarity'] = i8ln(value['rarity'])
            $.each(value['types'], function (key, pokemonType) {
                _types.push({
                    'type': i8ln(pokemonType['type']),
                    'color': pokemonType['color']
                })
            })
            value['types'] = _types
            idToPokemon[key] = value
        })

        // setup the filter lists
        $selectExclude.select2({
            placeholder: i8ln('Select Pokémon'),
            data: pokeList,
            templateResult: formatState
        })
        $selectPokemonNotify.select2({
            placeholder: i8ln('Select Pokémon'),
            data: pokeList,
            templateResult: formatState
        })
        $selectRarityNotify.select2({
            placeholder: i8ln('Select Rarity'),
            data: [i8ln('Common'), i8ln('Uncommon'), i8ln('Rare'), i8ln('Very Rare'), i8ln('Ultra Rare')],
            templateResult: formatState
        })

        // setup list change behavior now that we have the list to work from
        $selectExclude.on('change', function (e) {
            buffer = excludedPokemon
            excludedPokemon = $selectExclude.val().map(Number)
            buffer = buffer.filter(function (e) {
                return this.indexOf(e) < 0
            }, excludedPokemon)
            reincludedPokemon = reincludedPokemon.concat(buffer)
            clearStaleMarkers()
            Store.set('remember_select_exclude', excludedPokemon)
        })
        $selectPokemonNotify.on('change', function (e) {
            notifiedPokemon = $selectPokemonNotify.val().map(Number)
            Store.set('remember_select_notify', notifiedPokemon)
        })
        $selectRarityNotify.on('change', function (e) {
            notifiedRarity = $selectRarityNotify.val().map(String)
            Store.set('remember_select_rarity_notify', notifiedRarity)
        })
        $textPerfectionNotify.on('change', function (e) {
            notifiedMinPerfection = parseInt($textPerfectionNotify.val(), 10)
            if (isNaN(notifiedMinPerfection) || notifiedMinPerfection <= 0) {
                notifiedMinPerfection = ''
            }
            if (notifiedMinPerfection > 100) {
                notifiedMinPerfection = 100
            }
            $textPerfectionNotify.val(notifiedMinPerfection)
            Store.set('remember_text_perfection_notify', notifiedMinPerfection)
        })

        // recall saved lists
        $selectExclude.val(Store.get('remember_select_exclude')).trigger('change')
        $selectPokemonNotify.val(Store.get('remember_select_notify')).trigger('change')
        $selectRarityNotify.val(Store.get('remember_select_rarity_notify')).trigger('change')
        $textPerfectionNotify.val(Store.get('remember_text_perfection_notify')).trigger('change')
        $raidNotify.val(Store.get('remember_raid_notify')).trigger('change')

        if (isTouchDevice() && isMobileDevice()) {
            $('.select2-search input').prop('readonly', true)
        }
    })

    // run interval timers to regularly update map and timediffs
    window.setInterval(updateLabelDiffTime, 1000)
    window.setInterval(updateMap, 5000)
    window.setInterval(updateGeoLocation, 1000)

    createUpdateWorker()

    // Wipe off/restore map icons when switches are toggled
    function buildSwitchChangeListener(data, dataType, storageKey) {
        return function () {
            Store.set(storageKey, this.checked)
            if (this.checked) {
                // When switch is turned on we asume it has been off, makes sure we dont end up in limbo
                // Without this there could've been a situation where no markers are on map and only newly modified ones are loaded
                if (storageKey === 'showPokemon') {
                    lastpokemon = false
                } else if (storageKey === 'showRaids') {
                    lastgyms = false
                } else if (storageKey === 'showGyms') {
                    lastgyms = false
                } else if (storageKey === 'showPokestops') {
                    lastpokestops = false
                } else if (storageKey === 'showScanned') {
                    lastslocs = false
                } else if (storageKey === 'showSpawnpoints') {
                    lastspawns = false
                }
                updateMap()
            } else {
                $.each(dataType, function (d, dType) {
                    $.each(data[dType], function (key, value) {
                        // for any marker you're turning off, you'll want to wipe off the range
                        if (data[dType][key].marker.rangeCircle) {
                            data[dType][key].marker.rangeCircle.setMap(null)
                            delete data[dType][key].marker.rangeCircle
                        }
                        if (storageKey !== 'showRanges') data[dType][key].marker.setMap(null)
                    })
                    if (storageKey !== 'showRanges') data[dType] = {}
                })
                if (storageKey === 'showRanges') {
                    updateMap()
                }
            }
        }
    }

    // Setup UI element interactions
    $('#raids-switch').change(function () {
        var options = {
            'duration': 500
        }
        var wrapper = $('#raids-filter-wrapper')
        if (this.checked) {
            lastgyms = false
            wrapper.show(options)
        } else {
            lastgyms = false
            wrapper.hide(options)
        }
        buildSwitchChangeListener(mapData, ['gyms'], 'showRaids').bind(this)()
    })
    $('#gyms-switch').change(function () {
        var options = {
            'duration': 500
        }
        var wrapper2 = $('#gyms-filter-wrapper')
        if (this.checked) {
            lastgyms = false
            wrapper2.show(options)
        } else {
            lastgyms = false
            wrapper2.hide(options)
        }
        buildSwitchChangeListener(mapData, ['gyms'], 'showGyms').bind(this)()
    })
    $('#pokemon-switch').change(function () {
        buildSwitchChangeListener(mapData, ['pokemons'], 'showPokemon').bind(this)()
    })
    $('#scanned-switch').change(function () {
        buildSwitchChangeListener(mapData, ['scanned'], 'showScanned').bind(this)()
    })
    $('#spawnpoints-switch').change(function () {
        buildSwitchChangeListener(mapData, ['spawnpoints'], 'showSpawnpoints').bind(this)()
    })
    $('#ranges-switch').change(buildSwitchChangeListener(mapData, ['gyms', 'pokemons', 'pokestops'], 'showRanges'))

    $('#pokestops-switch').change(function () {
        var options = {
            'duration': 500
        }
        var wrapper = $('#lured-pokestops-only-wrapper')
        if (this.checked) {
            lastpokestops = false
            wrapper.show(options)
        } else {
            lastpokestops = false
            wrapper.hide(options)
        }
        return buildSwitchChangeListener(mapData, ['pokestops'], 'showPokestops').bind(this)()
    })

    $('#sound-switch').change(function () {
        Store.set('playSound', this.checked)
        var options = {
            'duration': 500
        }
        var wrapper = $('#cries-switch-wrapper')
        if (this.checked) {
            wrapper.show(options)
        } else {
            wrapper.hide(options)
        }
    })

    $('#cries-switch').change(function (){
        Store.set('playCries', this.checked)
    })

    $('#start-at-user-location-switch').change(function () {
        Store.set('startAtUserLocation', this.checked)
        if (this.checked === true && Store.get('startAtLastLocation') === true) {
            Store.set('startAtLastLocation', false)
            $('#start-at-last-location-switch').prop('checked', false)
        }
    })

    $('#start-at-last-location-switch').change(function () {
        Store.set('startAtLastLocation', this.checked)
        if (this.checked === true && Store.get('startAtUserLocation') === true) {
            Store.set('startAtUserLocation', false)
            $('#start-at-user-location-switch').prop('checked', false)
        }
    })

    $('#follow-my-location-switch').change(function () {
        if (!navigator.geolocation) {
            this.checked = false
        } else {
            Store.set('followMyLocation', this.checked)
        }
        locationMarker.setDraggable(!this.checked)
    })

    $('#spawn-area-switch').change(function () {
        Store.set('spawnArea', this.checked)
        if (locationMarker.rangeCircle) {
            locationMarker.rangeCircle.setMap(null)
            delete locationMarker.rangeCircle
        }
    })

    if ($('#nav-accordion').length) {
        $('#nav-accordion').accordion({
            active: false,
            collapsible: true,
            heightStyle: 'content'
        })
    }

    // Initialize dataTable in statistics sidebar
    //   - turn off sorting for the 'icon' column
    //   - initially sort 'name' column alphabetically

    $('#pokemonList_table').DataTable({
        paging: false,
        searching: false,
        info: false,
        errMode: 'throw',
        'language': {
            'emptyTable': ''
        },
        'columns': [{'orderable': false}, null, null, null]
    }).order([1, 'asc'])
})

function download(filename, text) { // eslint-disable-line no-unused-vars
    var element = document.createElement('a')
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text))
    element.setAttribute('download', filename + '_' + moment().format('DD-MM-YYYY HH:mm'))

    element.style.display = 'none'
    document.body.appendChild(element)

    element.click()

    document.body.removeChild(element)
}

function upload(fileText) {
    var data = JSON.parse(JSON.parse(fileText))
    Object.keys(data).forEach(function (k) {
        localStorage.setItem(k, data[k])
    })
    window.location.reload()
}

function openFile(event) { // eslint-disable-line no-unused-vars
    var input = event.target
    var reader = new FileReader()
    reader.onload = function () {
        console.log(reader.result)
        upload(reader.result)
    }
    reader.readAsText(input.files[0])
}

function checkAndCreateSound(pokemonId = 0) {
    if (Store.get('playSound')) {
        if (!Store.get('playCries') || pokemonId === 0) {
            createjs.Sound.play('ding')
        } else {
            createjs.Sound.play(pokemonId)
        }
    }
}