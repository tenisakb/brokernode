<?php
require_once('../Converter.php');
require_once('../Helper.php');
require_once('../kerl-php\kerl.php');
require_once('../wordsFunctionsPHP.php');
require_once('../Bundle.php');



$newLineChar = "<br/>";
//toggle between \n or <br/>
//depending on whether you are
//debugging in browser or terminal.


/*Optional TODOS:
    -abstract newLineChar and newLine method to their own file so dev can change it globally in one place
    -add folder for test utilities to abstract away such files
    -create file to require_once all tests into so a dev could run all test files at once
    -create mock of Kerl if we can't get PHP keccak implementation working
*/


//TEST VALUES
$dummyTrytes = [
    'GYOMKVTSNHVJNCNFBBAH9AAMXLPLLLROQY99QN9DLSJUHDPBLCFFAIQXZA9BKMBJCYSFHFPXAHDWZFEIZ',
    'OXJCNFHUNAHWDLKKPELTBFUCVW9KLXKOGWERKTJXQMXTKFKNWNNXYD9DMJJABSEIONOSJTTEVKVDQEWTW',
    'XQMXTKFKNWNNXYD9DMJJABSEIONOSJTTEVKVDQEWTWGYOMKVTSNHVJNCNFBBAH9AAMXLPLLLROQY99QN9',
];

$dummyValue = 'VTSNHVJNCCYSFHLKKPELTBFFPXAHDWZFENLAIQXZ9LROGYOMKVTSNHVJNQY99QNFBBAH9AAYOMKVTSNHV';


//BEGIN TESTS
newLine("Constructor");
newLine("    It should initialize an empty bundle array.");

$testBundle = new Bundle();

if ($testBundle->bundle == []) {
    newLine("        Success!");
} else {
    newLine("        FAILED!");
}

newLine("addEntry");
newLine("    It should add a certain number of transaction objects to the bundle array based on the length passed in.");

$testBundle->addEntry(
    4,
    'dummyAddress',
    $dummyValue,
    'dummyTag',
    'Four score and seven years ago',
    0 //JS functions have this index param but we don't seem to need it
);

if (count($testBundle->bundle) === 4) {
    newLine("        Success!");
} else {
    newLine("        FAILED!");
}

newLine("    It should add certain properties to each entry in the bundle.");

if (property_exists($testBundle->bundle[0], 'address') &&
    property_exists($testBundle->bundle[0], 'value') &&
    property_exists($testBundle->bundle[0], 'obsoleteTag') &&
    property_exists($testBundle->bundle[0], 'tag') &&
    property_exists($testBundle->bundle[0], 'timestamp')) {
    newLine("        Success!");
} else {
    newLine("        FAILED!");
}

newLine("    Value should be 0 for the value property of all but the first entry, and should be the value passed in for the first.");

if ($testBundle->bundle[0]->value === $dummyValue &&
    $testBundle->bundle[1]->value === 0 &&
    $testBundle->bundle[2]->value === 0 &&
    $testBundle->bundle[3]->value === 0) {
    newLine("        Success!");
} else {
    newLine("        FAILED!");
}

newLine("addTrytes");
newLine("    It should add additional properties to every bundle entry.");

$testBundle->addTrytes($dummyTrytes);

$addTrytesResult = "        Success!";

for ($i = 0; $i < count($testBundle->bundle); $i++) {
    if (!property_exists($testBundle->bundle[$i], 'signatureMessageFragment') ||
        !property_exists($testBundle->bundle[$i], 'trunkTransaction') ||
        !property_exists($testBundle->bundle[$i], 'branchTransaction') ||
        !property_exists($testBundle->bundle[$i], 'attachmentTimestamp') ||
        !property_exists($testBundle->bundle[$i], 'attachmentTimestampLowerBound') ||
        !property_exists($testBundle->bundle[$i], 'attachmentTimestampUpperBound') ||
        !property_exists($testBundle->bundle[$i], 'attachmentTimestampUpperBound') ||
        !property_exists($testBundle->bundle[$i], 'nonce')) {
        $addTrytesResult = "        FAILED!";
    }
}

newLine($addTrytesResult);

newLine("    If a signature fragment was passed in, it should use that signature fragment.");

if ($testBundle->bundle[0]->signatureMessageFragment === $dummyTrytes[0] &&
    $testBundle->bundle[1]->signatureMessageFragment === $dummyTrytes[1] &&
    $testBundle->bundle[2]->signatureMessageFragment === $dummyTrytes[2]) {
    newLine("        Success!");
} else {
    newLine("        FAILED!");
}

newLine("    If a signature fragment was NOT passed in, it should set the signature message fragment to a string of 2187 9s.");

$wholeLottaNines = '';

for ($i = 0; $i < 2187; $i++) {
    $wholeLottaNines .= '9';
}

if ($testBundle->bundle[3]->signatureMessageFragment === $wholeLottaNines) {
    newLine("        Success!");
} else {
    newLine("        FAILED!");
}

newLine("finalize");
newLine("    It create a hash and assign that hash value to the 'bundle' property of each entry.");

/*
For the time being, we probably don't want call the Kerl service from the Bundle tests because we
are using node.  In the future we'd want a PHP implementation of the Keccak algorithms so this would
not be an issue.  For the time being, developers can manually add the following lines of code to the
bottom of the Bundle.php file and verify the functionality of the finalize and normalizedBundle methods.
I have also included some javascript code; add it to the bottom of the bundle.js file in the iota.lib.js
library and run the file with node, to verify that its output is the same as our PHP Bundle.

These lines of code test finalize() as well as normalizedBundle() since finalize() calls
normalizedBundle() when a bundle is invalid.

Reference:
https://iota.readme.io/v1.2.0/docs/bundles

------------PHP-------------

$bundle = [];

$bundle[0] = new stdClass();
$bundle[0]->signatureMessageFragment = '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999';
$bundle[0]->address = 'PPJFBGPEFOMNBNTTCFBTQCGY9BVXTS9LWQVFNXODIHVEPJLFRSJYMYBDFOOFWPIBAYBLBZO9PHYOLEJBA';
$bundle[0]->value = 1;
$bundle[0]->obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[0]->timestamp = 1502215514;
$bundle[0]->currentIndex = 0;
$bundle[0]->lastIndex = 3;
$bundle[0]->trunkTransaction = 'UEBOMWXEKEQCZFOTGRDF9MXZQDXX9VBKQNKLQDTNMNSY9RM9ZVPHVNXOIYEJLBIPE9JSHMWQPPXZ99999';
$bundle[0]->branchTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[0]->tag = 'VUTFNPTRCHCH9JDRPPEJHJDZKJX';
$bundle[0]->attachmentTimestamp = -1789972691417;
$bundle[0]->attachmentTimestampLowerBound = -442481168685;
$bundle[0]->attachmentTimestampUpperBound = -1558788566106;
$bundle[0]->nonce = 'RJTBFCUPVBRCZNAVV9HPS9IRKVM';

$bundle[1] = new stdClass();
$bundle[1]->signatureMessageFragment = 'QHYTTDPBSHHHFXNQWXWMOINNIOUXCMJTZGWSCJEMWFUVDSYSRHABDKLPXFGHX9DVOPGCE9CNDPQEYOLRWADEPEQVIAXHVGALDOPHJGSQYFPOCEOZQRVIVNXES9DPCGBYIDIUZRGI9PGZWYHOXNCMDXT9YRXWVBPPABP9XQFS9SNUFIDS9ZDDONYHYGNNEMAAM9XOGYPFIGVXAVEQMTXXLJZFOCILIWUYODUYERZLVFSCRO9PAFWCIGSUQQOAZQOYJZIOMTVCVFLXTCNLIUMVM9FREBSBLEFGWRFGNV9XPOJYVXMXROYDUUWHRSVIOMFHSEVZDNERZMZPVHSYUVCQLKZGDTQNOHSBORQJBLDLDM9OBVJXMMPRTQADQCMPOETPZSGXGYUA9QOWJDBTWXEXBCPLPKVATHQQWTUNDCHCAKOOFWPCOFCRCUMFGWTTSRVNPIQJMOGQJV9UGBVWDQLCGGDJUC9QII9SOYDOGXC9VIPZKOAYL9BSTIUXUPESUMBWYZHXLEAAGCVYHNSUUUGDVILVSUTQBIFSPVQHWGNCQVOV9UQMHAWXBEZFEGHRB9RARBVRHQN9VXVJSCMEQMAFSYXLRZLSPHJUPTJXRQ9AHJWQDBBMKVEPRUIDQJGUVHVMDXNTDUCYTBYGZHZVQFEBGYZGSUPN9GHXLDQGH9RMVSYQ9VTHQXOXRDISURXPYTMLAUKQDCOLXTPRNFYNTZHDYH9UWLTNP9AZMSNV9EIDNUGPQUADTBHO9EKMUHEZKHVZJYZXMUMCONPD9KXXZDWBH9YLZNFVKI9BUWKGAMEUKD9AGGXZGAFWDLJF9XADITKONBUTGUHYYWYKKSCGSFUNGIG9EJIPUETMBDUNGZUPXKEYTQCVKIEIQTURL9XEVPT9RBFHXMCFYDGPRTFUWZWMYRMQVKNYAHKRZIBDN9DWQ9CDUSQBFTDSOTQKCZORVSMIHPJBJ9HWHFG9IUZVOYTQWBMRG9VBSQVTIDBREOXNRARNPAYPLLQGJRWRNJLFE99MEXVOKGEH99VFBAVCJOYTVDMSTRDWBDIEWVWLNZEJCNHIRUKZEPWVFGGITVQLMTDYHSSCHUYPCRDWRRATKHMUWFLRMODOTIYBDVVFPJMHUMFXKDTBGYIRYIHAPFHHOCURRJVTIOSLUMVYCTQKLTZVDJIETCETCQXJITTGMTMXNDGIHAMAPVDEPXRXXMBO9ZWOUJJWHWUFRHLAKGCLIQCPWDJIVZILIWUSNDYPSOEGRAEJGNQBDYWCXZCSNQ9AZFLZ9GDCBJTHXBD9IQLXDUZ9ICEVSOLSTIEQUJNYH9OOZJIVHTDZMIBZ9SVBBBGTEGTCKKUEQZOMDEXXRFLKTDXKGHVXMDOSQGJXDWYRZ9PNSEAZNSZFAJQAAACNHZDMBIBKHAIC9HXELOEGCYN9LVZCDJ9XRDJSNFMBGVZAQZLCCSDUPRRYFCQRBRYMFYEAM9SZWC9JBLQEQJZCWFLZKUKQJTOGRMRCRGKMPYRAIBSQARAZXETXDSRLIGQIBMPQCQAIFVZKJVTHBFT9PRBTHVGCGXFPRBDWLBDRHVJISDNRQEUUIUXMNVHRIUSMIQAMIQHEMF9OBWPUZWHIUEFAMBHZHDGKMKFL9SKTURRPJOKYM9CJYOYTGPRDPOUUZQUTYDBPUDLOPRAVWUWZRRNZGCLVNUATOYALLKQRWDCEDSJKKJWOQFIAMA9XLJYEYWOOUUWGQIOSEAJLOPCXWBEPYURZZRZOYTZVGRCWMOQZGAUGGWFNZHIHHUCAKZPLEQFWBVKLFYIXIJSEQLEFWRUSJFKVIBGHNESKHBYXNUSTUNSSBUJNCETWZ9MZXNHQQWCIDEJFDYBXWTXTWSOUDTUIITZR9OMZNZXDAIOCPSPRAUQRZDBWNFT9OXAOINSXXJSLVSHAHDGHLJAJIDEHNOFJXNTJZ9XAEHTNWKMDFEYCWZ9VYERIXNIMELVOYVGTIB99ACZUJYUMP9MQKPOFIRFMMWQ9YSWHLYLZ9QANFECFLKWBXYEOBH9MYACFUSLVCCWCIJDGJPWZBNGJUKYYIFIESAUAMW9Q9GDKGCMOSFANYUTZTUXEEQJWQCFNMGTLPZDZNRVGPXPSFOFNGKZGNNPQWWEIFOGLHOEUSAOIMKI9JFFBGLQQMWQJPJNOQFSMCJTRIRTVLEQIIIVVICD';
$bundle[1]->address = 'ZWXPIOIUFRIC9EZCUUJWEOZKBWH9ROHCIP9WRZGUXQZMUTMTGGEBPHBUMWK9GTLEXCIVWWR9ZAENHKSJ9';
$bundle[1]->value = -2000000000;
$bundle[1]->obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[1]->timestamp = 1502215514;
$bundle[1]->currentIndex = 1;
$bundle[1]->lastIndex = 3;
$bundle[1]->trunkTransaction = 'YBRMGZC9SHQNUNDGZEIHETCZJX9DZJ9AAXHGSPQWEAIVC9OSZWVUGBMVX9MUWYJVNNUJTCNOTUGP99999';
$bundle[1]->branchTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[1]->tag = 'KOXCGHOATHMIGGHJX9PJYKYCEWI';
$bundle[1]->attachmentTimestamp = -3519568744220;
$bundle[1]->attachmentTimestampLowerBound = -2402437163849;
$bundle[1]->attachmentTimestampUpperBound = 2116140926633;
$bundle[1]->nonce = 'MFSLEXDI9CVT9LE99XOGC9ABHOV';

$bundle[2] = new stdClass();
$bundle[2]->signatureMessageFragment = 'ICJAYWSUQXPRGDBXWGOVLAIHIGTRCLPBZIFMCKWBF9MWICVBGMYYNTSVWDUNALFZMSLMGKTILEUULOBMDCHTKABBHXU9RCNRAGDPEDJLULAXEZYTQQUAXZYARFXUGFDZLRPZSTXLOJFIMBUGHJNLWJNOMULNQOVCBYFNLSZZH9YCEA99UEEGEDXNSISHHMBRSAADCYIP9XICGFSPRLNMXMFVGWHKDUZQLZBKHDVBOAZQBFSYDDYZHQOVYJZAKMFXFIWCMGO9VTYZEAVGEDZFKY9VMTLDWOKSYFUGUF9IWUTZMTHJINKFQ9CNPKUVGXKEJXJZICZSSFHZYDFOKNINWU9IFJUKETQPSUBAKOKFNEWEAYXZEHVDUUATVGRFBDWNTBPETJECHXPUHRGWXOKOYVUJPQKASXBYWDZFGMHAAQGDCQWEAFFGMMANYZ9JGJURTQCLJBGOUQVYVGRWWGHRRGKYSSIDHZZLPVNYPAJYMMHD9EKOYAYVWWVPUHZQTFBOXJQIFVOYXSNQ9XQISMIZAQELLVRVFXQKLKTZSRJNBRPNFFGOTSMDADAYSVVUDPDXYETLZVPH9BSFOJMHVENEIRHKNV9GOEYHZIOSJIMNTUMTBEGFBMZQGXQYADGRE9MPHVXHGFT9YSRAIS9YMMXBYACDQVIXOQLRQSVPGPLHGZUSBTSOEJNEVSEZBYRUDHUJMEDDZRNBUPSDWIDXDKHXHUIE9KSCGYE9PKBTSKRXMCERJWCUENIGJKHKFKXYEXSGFHKYGXCVYETWQERNDIZACUPLXFKBFOLTGYOQIPFX9YPCVIYVNYACOFYSPKHHLKSBXIJQDZLQ9JWPDICSXWTUWHAQIRWHUFHZNYMJMJCVIZWPBGURYIDQSYDTHMBHSB9LFOCGVNNZEXFSQUGFS9IGGNBYDVXQSATZTVKFUYCWQXISDQWFVUJQVQQQBBKGZDZETQKHYQIHDUMYULXVYEX9SKILMGPP9CJPWIWHCFGMCWGOXAGRVFIABEQBSSRFSAAOAYRAQOQWTUIMD9EFTWJZRLHCBRCOWSORAPFRGGSZECFQQDFKWYRSDPLLXJLNZJJMYOG9QZYWEIWY9KWPBPGJZCJZIFDCNFTUGIN9VMJL9XHRJXWNYTBZCYHKTKHWKDGVUDIZJPMXOYUHUVCOULMRRBYH9CJHVGMVLNKZFMYXQFJSYRLILZISXVMTNQ9BLADY9YDBPUOFBQTFJWYUZX99Z9LFJJ9WV9NAMBUEXOZQTNIGPKDEYE9FTO99SSMCCCGWD9DDVAFKRP9OMAGBSHZMORJTIPHOHKT9YEAQCFIULKTYJWUL9ABACZRZUMFZSWFBLOZUUTBSKRLPQCDZNQIWPRXYEZSLWTFRYKWZPHNUXII9MWFCKBEOEXOJC9ITEQNQPQPQUHVQZDJEZNIMRTIEYW99ZIZGVH9QEVCOWZKXAYNCMDWNBWTDAQDMAXCPHTTLUOIXTWLZNRTVSLKQFAQHRJSCMWD9XRFNCXNRUDOILEJPXRAXUCJNMHYBVONKBQYDGMDOPMVJNURTADTGVASPCB9ZPQVWIRI9VVKBTZE99MFGFHFCESXMTXFGLMELWORBZTEMEEZHEEFHRCPY9DMZHPXYI9CPDREJSECKCSIEHUQJACGNPWIOSUCARELRIFHMC9GCRIKK9PVKUCYMILFMRMKCYPVOAIIAVIDNXEXDNMLE9FLUGVFROOJMPRYWXDNWGWTSFNMZPIPWDZOVWLUBQSMJPZUABZJAYN9KLRULXKUXMRLRWYKIE9EYYCODKHCRGZHKOIRSQBP9LAFQAUPPKYNJ9SOMOCPHMRHIYAEKXZMAXZBAQZBDTUBGKQAONOIVBKITECYVELHCST9QHWGALGNBYUNQNALDRZLZIJDPABN9MWDIVTVHHTOHFKFMIYSTOSRXAOYOMCYOLZZSMGWF9KWDMVMMYRPOHHWZKOXYQYAUPWXIPZTXKNZXLUSKZGEMGODFEM9AAOMNH9JDAYIJRA9WBYXGHTZJCUVYQXCXWH9WKLVQBNUYETQPTCRSL9GNARBVQUPEOXSUVNPOCAPWHWJWZNOPGCJLFLIACQZRN9XYNZOU9DZSBTTUUXVAUOVZWDHIZHNWA9KSUWCNU9C9OQXIZYUUQP9QKEOXUIXYTHTRZJIAUKSKFFNVGIPVJGVNVKHDQTTMXKA';
$bundle[2]->address = 'ZWXPIOIUFRIC9EZCUUJWEOZKBWH9ROHCIP9WRZGUXQZMUTMTGGEBPHBUMWK9GTLEXCIVWWR9ZAENHKSJ9';
$bundle[2]->value = 0;
$bundle[2]->obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[2]->timestamp = 1502215514;
$bundle[2]->currentIndex = 2;
$bundle[2]->lastIndex = 3;
$bundle[2]->trunkTransaction = 'F9AYXWCOWNCRJKTGHRYAURGOCMEXHWQE9YYCLNPOWZECDKPATVVBA9VJJBMEWVPNYYFNYWNDPMUU99999';
$bundle[2]->branchTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[2]->tag = '9GDNXRQSWHOVQWCUXESHIHTSGG9';
$bundle[2]->attachmentTimestamp = -1576624671529;
$bundle[2]->attachmentTimestampLowerBound = 116988204706;
$bundle[2]->attachmentTimestampUpperBound = -2233110606683;
$bundle[2]->nonce = 'KESGKIIXKGV9BPIBCXLBYBBVTCH';

$bundle[3] = new stdClass();
$bundle[3]->signatureMessageFragment = '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999';
$bundle[3]->address = 'BRU9LRH9YNGTYMFRODMPMEAPIDTDYUFLQLFWTXKNXQYHSYNEMCFVFGKMIWWOWDFOAJ9RRZVCWX9ELQEP9';
$bundle[3]->value = 1999999999;
$bundle[3]->obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[3]->timestamp = 1502215528;
$bundle[3]->currentIndex = 3;
$bundle[3]->lastIndex = 3;
$bundle[3]->trunkTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[3]->branchTransaction = 'TRKDBAIFTWTNRMCLVGBSXJXZO9VNFMYOSDJXELM9LNUHXOQBFRMNAAZTWURMNGUZDJVXNITXWZKAZ9999';
$bundle[3]->tag = 'MIRMAZTQUR9MMEPCWOMHMDLZPFE';
$bundle[3]->attachmentTimestamp = -1737679689424;
$bundle[3]->attachmentTimestampLowerBound = -282646045775;
$bundle[3]->attachmentTimestampUpperBound = 2918881518838;
$bundle[3]->nonce = 'IJZRLQMGVIYWOS9FDKDRPONJWNB';


$bundleObject = new Bundle();

$bundleObject->bundle = $bundle;

$bundleObject->finalize();

$expected = 'KVFRBPDHXHIAFJLTSAVWZGCYIZVPNZ9ZEYKRZBO9OSHZTZCJIIYFJDXNPVXNYXGCTLLCBLCILORUWBEWA';

$newLine = "<br/>";
//$newLine = "\n";

echo $newLine;
echo $newLine;
echo $bundleObject->bundle[0]->bundle . $newLine;
echo $bundleObject->bundle[1]->bundle . $newLine;
echo $bundleObject->bundle[2]->bundle . $newLine;
echo $bundleObject->bundle[3]->bundle . $newLine;
echo $newLine;
echo $newLine;
echo "Expected output was: " . $newLine;
echo $expected . $newLine;



------------JS--------------



var $bundle = [ {}, {}, {}, {} ];

$bundle[0].signatureMessageFragment = '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999';
$bundle[0].address = 'PPJFBGPEFOMNBNTTCFBTQCGY9BVXTS9LWQVFNXODIHVEPJLFRSJYMYBDFOOFWPIBAYBLBZO9PHYOLEJBA';
$bundle[0].value = 1;
$bundle[0].obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[0].timestamp = 1502215514;
$bundle[0].currentIndex = 0;
$bundle[0].lastIndex = 3;
$bundle[0].trunkTransaction = 'UEBOMWXEKEQCZFOTGRDF9MXZQDXX9VBKQNKLQDTNMNSY9RM9ZVPHVNXOIYEJLBIPE9JSHMWQPPXZ99999';
$bundle[0].branchTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[0].tag = 'VUTFNPTRCHCH9JDRPPEJHJDZKJX';
$bundle[0].attachmentTimestamp = -1789972691417;
$bundle[0].attachmentTimestampLowerBound = -442481168685;
$bundle[0].attachmentTimestampUpperBound = -1558788566106;
$bundle[0].nonce = 'RJTBFCUPVBRCZNAVV9HPS9IRKVM';

$bundle[1].signatureMessageFragment = 'QHYTTDPBSHHHFXNQWXWMOINNIOUXCMJTZGWSCJEMWFUVDSYSRHABDKLPXFGHX9DVOPGCE9CNDPQEYOLRWADEPEQVIAXHVGALDOPHJGSQYFPOCEOZQRVIVNXES9DPCGBYIDIUZRGI9PGZWYHOXNCMDXT9YRXWVBPPABP9XQFS9SNUFIDS9ZDDONYHYGNNEMAAM9XOGYPFIGVXAVEQMTXXLJZFOCILIWUYODUYERZLVFSCRO9PAFWCIGSUQQOAZQOYJZIOMTVCVFLXTCNLIUMVM9FREBSBLEFGWRFGNV9XPOJYVXMXROYDUUWHRSVIOMFHSEVZDNERZMZPVHSYUVCQLKZGDTQNOHSBORQJBLDLDM9OBVJXMMPRTQADQCMPOETPZSGXGYUA9QOWJDBTWXEXBCPLPKVATHQQWTUNDCHCAKOOFWPCOFCRCUMFGWTTSRVNPIQJMOGQJV9UGBVWDQLCGGDJUC9QII9SOYDOGXC9VIPZKOAYL9BSTIUXUPESUMBWYZHXLEAAGCVYHNSUUUGDVILVSUTQBIFSPVQHWGNCQVOV9UQMHAWXBEZFEGHRB9RARBVRHQN9VXVJSCMEQMAFSYXLRZLSPHJUPTJXRQ9AHJWQDBBMKVEPRUIDQJGUVHVMDXNTDUCYTBYGZHZVQFEBGYZGSUPN9GHXLDQGH9RMVSYQ9VTHQXOXRDISURXPYTMLAUKQDCOLXTPRNFYNTZHDYH9UWLTNP9AZMSNV9EIDNUGPQUADTBHO9EKMUHEZKHVZJYZXMUMCONPD9KXXZDWBH9YLZNFVKI9BUWKGAMEUKD9AGGXZGAFWDLJF9XADITKONBUTGUHYYWYKKSCGSFUNGIG9EJIPUETMBDUNGZUPXKEYTQCVKIEIQTURL9XEVPT9RBFHXMCFYDGPRTFUWZWMYRMQVKNYAHKRZIBDN9DWQ9CDUSQBFTDSOTQKCZORVSMIHPJBJ9HWHFG9IUZVOYTQWBMRG9VBSQVTIDBREOXNRARNPAYPLLQGJRWRNJLFE99MEXVOKGEH99VFBAVCJOYTVDMSTRDWBDIEWVWLNZEJCNHIRUKZEPWVFGGITVQLMTDYHSSCHUYPCRDWRRATKHMUWFLRMODOTIYBDVVFPJMHUMFXKDTBGYIRYIHAPFHHOCURRJVTIOSLUMVYCTQKLTZVDJIETCETCQXJITTGMTMXNDGIHAMAPVDEPXRXXMBO9ZWOUJJWHWUFRHLAKGCLIQCPWDJIVZILIWUSNDYPSOEGRAEJGNQBDYWCXZCSNQ9AZFLZ9GDCBJTHXBD9IQLXDUZ9ICEVSOLSTIEQUJNYH9OOZJIVHTDZMIBZ9SVBBBGTEGTCKKUEQZOMDEXXRFLKTDXKGHVXMDOSQGJXDWYRZ9PNSEAZNSZFAJQAAACNHZDMBIBKHAIC9HXELOEGCYN9LVZCDJ9XRDJSNFMBGVZAQZLCCSDUPRRYFCQRBRYMFYEAM9SZWC9JBLQEQJZCWFLZKUKQJTOGRMRCRGKMPYRAIBSQARAZXETXDSRLIGQIBMPQCQAIFVZKJVTHBFT9PRBTHVGCGXFPRBDWLBDRHVJISDNRQEUUIUXMNVHRIUSMIQAMIQHEMF9OBWPUZWHIUEFAMBHZHDGKMKFL9SKTURRPJOKYM9CJYOYTGPRDPOUUZQUTYDBPUDLOPRAVWUWZRRNZGCLVNUATOYALLKQRWDCEDSJKKJWOQFIAMA9XLJYEYWOOUUWGQIOSEAJLOPCXWBEPYURZZRZOYTZVGRCWMOQZGAUGGWFNZHIHHUCAKZPLEQFWBVKLFYIXIJSEQLEFWRUSJFKVIBGHNESKHBYXNUSTUNSSBUJNCETWZ9MZXNHQQWCIDEJFDYBXWTXTWSOUDTUIITZR9OMZNZXDAIOCPSPRAUQRZDBWNFT9OXAOINSXXJSLVSHAHDGHLJAJIDEHNOFJXNTJZ9XAEHTNWKMDFEYCWZ9VYERIXNIMELVOYVGTIB99ACZUJYUMP9MQKPOFIRFMMWQ9YSWHLYLZ9QANFECFLKWBXYEOBH9MYACFUSLVCCWCIJDGJPWZBNGJUKYYIFIESAUAMW9Q9GDKGCMOSFANYUTZTUXEEQJWQCFNMGTLPZDZNRVGPXPSFOFNGKZGNNPQWWEIFOGLHOEUSAOIMKI9JFFBGLQQMWQJPJNOQFSMCJTRIRTVLEQIIIVVICD';
$bundle[1].address = 'ZWXPIOIUFRIC9EZCUUJWEOZKBWH9ROHCIP9WRZGUXQZMUTMTGGEBPHBUMWK9GTLEXCIVWWR9ZAENHKSJ9';
$bundle[1].value = -2000000000;
$bundle[1].obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[1].timestamp = 1502215514;
$bundle[1].currentIndex = 1;
$bundle[1].lastIndex = 3;
$bundle[1].trunkTransaction = 'YBRMGZC9SHQNUNDGZEIHETCZJX9DZJ9AAXHGSPQWEAIVC9OSZWVUGBMVX9MUWYJVNNUJTCNOTUGP99999';
$bundle[1].branchTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[1].tag = 'KOXCGHOATHMIGGHJX9PJYKYCEWI';
$bundle[1].attachmentTimestamp = -3519568744220;
$bundle[1].attachmentTimestampLowerBound = -2402437163849;
$bundle[1].attachmentTimestampUpperBound = 2116140926633;
$bundle[1].nonce = 'MFSLEXDI9CVT9LE99XOGC9ABHOV';

$bundle[2].signatureMessageFragment = 'ICJAYWSUQXPRGDBXWGOVLAIHIGTRCLPBZIFMCKWBF9MWICVBGMYYNTSVWDUNALFZMSLMGKTILEUULOBMDCHTKABBHXU9RCNRAGDPEDJLULAXEZYTQQUAXZYARFXUGFDZLRPZSTXLOJFIMBUGHJNLWJNOMULNQOVCBYFNLSZZH9YCEA99UEEGEDXNSISHHMBRSAADCYIP9XICGFSPRLNMXMFVGWHKDUZQLZBKHDVBOAZQBFSYDDYZHQOVYJZAKMFXFIWCMGO9VTYZEAVGEDZFKY9VMTLDWOKSYFUGUF9IWUTZMTHJINKFQ9CNPKUVGXKEJXJZICZSSFHZYDFOKNINWU9IFJUKETQPSUBAKOKFNEWEAYXZEHVDUUATVGRFBDWNTBPETJECHXPUHRGWXOKOYVUJPQKASXBYWDZFGMHAAQGDCQWEAFFGMMANYZ9JGJURTQCLJBGOUQVYVGRWWGHRRGKYSSIDHZZLPVNYPAJYMMHD9EKOYAYVWWVPUHZQTFBOXJQIFVOYXSNQ9XQISMIZAQELLVRVFXQKLKTZSRJNBRPNFFGOTSMDADAYSVVUDPDXYETLZVPH9BSFOJMHVENEIRHKNV9GOEYHZIOSJIMNTUMTBEGFBMZQGXQYADGRE9MPHVXHGFT9YSRAIS9YMMXBYACDQVIXOQLRQSVPGPLHGZUSBTSOEJNEVSEZBYRUDHUJMEDDZRNBUPSDWIDXDKHXHUIE9KSCGYE9PKBTSKRXMCERJWCUENIGJKHKFKXYEXSGFHKYGXCVYETWQERNDIZACUPLXFKBFOLTGYOQIPFX9YPCVIYVNYACOFYSPKHHLKSBXIJQDZLQ9JWPDICSXWTUWHAQIRWHUFHZNYMJMJCVIZWPBGURYIDQSYDTHMBHSB9LFOCGVNNZEXFSQUGFS9IGGNBYDVXQSATZTVKFUYCWQXISDQWFVUJQVQQQBBKGZDZETQKHYQIHDUMYULXVYEX9SKILMGPP9CJPWIWHCFGMCWGOXAGRVFIABEQBSSRFSAAOAYRAQOQWTUIMD9EFTWJZRLHCBRCOWSORAPFRGGSZECFQQDFKWYRSDPLLXJLNZJJMYOG9QZYWEIWY9KWPBPGJZCJZIFDCNFTUGIN9VMJL9XHRJXWNYTBZCYHKTKHWKDGVUDIZJPMXOYUHUVCOULMRRBYH9CJHVGMVLNKZFMYXQFJSYRLILZISXVMTNQ9BLADY9YDBPUOFBQTFJWYUZX99Z9LFJJ9WV9NAMBUEXOZQTNIGPKDEYE9FTO99SSMCCCGWD9DDVAFKRP9OMAGBSHZMORJTIPHOHKT9YEAQCFIULKTYJWUL9ABACZRZUMFZSWFBLOZUUTBSKRLPQCDZNQIWPRXYEZSLWTFRYKWZPHNUXII9MWFCKBEOEXOJC9ITEQNQPQPQUHVQZDJEZNIMRTIEYW99ZIZGVH9QEVCOWZKXAYNCMDWNBWTDAQDMAXCPHTTLUOIXTWLZNRTVSLKQFAQHRJSCMWD9XRFNCXNRUDOILEJPXRAXUCJNMHYBVONKBQYDGMDOPMVJNURTADTGVASPCB9ZPQVWIRI9VVKBTZE99MFGFHFCESXMTXFGLMELWORBZTEMEEZHEEFHRCPY9DMZHPXYI9CPDREJSECKCSIEHUQJACGNPWIOSUCARELRIFHMC9GCRIKK9PVKUCYMILFMRMKCYPVOAIIAVIDNXEXDNMLE9FLUGVFROOJMPRYWXDNWGWTSFNMZPIPWDZOVWLUBQSMJPZUABZJAYN9KLRULXKUXMRLRWYKIE9EYYCODKHCRGZHKOIRSQBP9LAFQAUPPKYNJ9SOMOCPHMRHIYAEKXZMAXZBAQZBDTUBGKQAONOIVBKITECYVELHCST9QHWGALGNBYUNQNALDRZLZIJDPABN9MWDIVTVHHTOHFKFMIYSTOSRXAOYOMCYOLZZSMGWF9KWDMVMMYRPOHHWZKOXYQYAUPWXIPZTXKNZXLUSKZGEMGODFEM9AAOMNH9JDAYIJRA9WBYXGHTZJCUVYQXCXWH9WKLVQBNUYETQPTCRSL9GNARBVQUPEOXSUVNPOCAPWHWJWZNOPGCJLFLIACQZRN9XYNZOU9DZSBTTUUXVAUOVZWDHIZHNWA9KSUWCNU9C9OQXIZYUUQP9QKEOXUIXYTHTRZJIAUKSKFFNVGIPVJGVNVKHDQTTMXKA';
$bundle[2].address = 'ZWXPIOIUFRIC9EZCUUJWEOZKBWH9ROHCIP9WRZGUXQZMUTMTGGEBPHBUMWK9GTLEXCIVWWR9ZAENHKSJ9';
$bundle[2].value = 0;
$bundle[2].obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[2].timestamp = 1502215514;
$bundle[2].currentIndex = 2;
$bundle[2].lastIndex = 3;
$bundle[2].trunkTransaction = 'F9AYXWCOWNCRJKTGHRYAURGOCMEXHWQE9YYCLNPOWZECDKPATVVBA9VJJBMEWVPNYYFNYWNDPMUU99999';
$bundle[2].branchTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[2].tag = '9GDNXRQSWHOVQWCUXESHIHTSGG9';
$bundle[2].attachmentTimestamp = -1576624671529;
$bundle[2].attachmentTimestampLowerBound = 116988204706;
$bundle[2].attachmentTimestampUpperBound = -2233110606683;
$bundle[2].nonce = 'KESGKIIXKGV9BPIBCXLBYBBVTCH';

$bundle[3].signatureMessageFragment = '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999';
$bundle[3].address = 'BRU9LRH9YNGTYMFRODMPMEAPIDTDYUFLQLFWTXKNXQYHSYNEMCFVFGKMIWWOWDFOAJ9RRZVCWX9ELQEP9';
$bundle[3].value = 1999999999;
$bundle[3].obsoleteTag = 'BIGTEST99999999999999999999';
$bundle[3].timestamp = 1502215528;
$bundle[3].currentIndex = 3;
$bundle[3].lastIndex = 3;
$bundle[3].trunkTransaction = 'GPWZXISZREDVZTRCPSZAJYPDHNFF9AGZXIYWIDLVWZDHBIQRPSJPXAUNRMXCZLIRSBHBILATOQYQX9999';
$bundle[3].branchTransaction = 'TRKDBAIFTWTNRMCLVGBSXJXZO9VNFMYOSDJXELM9LNUHXOQBFRMNAAZTWURMNGUZDJVXNITXWZKAZ9999';
$bundle[3].tag = 'MIRMAZTQUR9MMEPCWOMHMDLZPFE';
$bundle[3].attachmentTimestamp = -1737679689424;
$bundle[3].attachmentTimestampLowerBound = -282646045775;
$bundle[3].attachmentTimestampUpperBound = 2918881518838;
$bundle[3].nonce = 'IJZRLQMGVIYWOS9FDKDRPONJWNB';


var $bundleObject = new Bundle();

$bundleObject.bundle = $bundle;

$bundleObject.finalize();

console.log("\n");
console.log("\n");
console.log($bundleObject.bundle[0].bundle);
console.log($bundleObject.bundle[1].bundle);
console.log($bundleObject.bundle[2].bundle);
console.log($bundleObject.bundle[3].bundle);
console.log("Expected output was: ");
console.log("KVFRBPDHXHIAFJLTSAVWZGCYIZVPNZ9ZEYKRZBO9OSHZTZCJIIYFJDXNPVXNYXGCTLLCBLCILORUWBEWA");


 */



//This is just so we don't have to keep adding "\n" to our print statements
function newLine($stringToPrint)
{
    global $newLineChar;

    echo $newLineChar . $stringToPrint . $newLineChar . $newLineChar;
}