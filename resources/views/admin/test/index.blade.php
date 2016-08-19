<?php
use App\Helpers\ImageHelper;

// $source_file = Resize::img('aaaaa.png','mainMedia');
// $source_file = Resize::img('N6xCpRAE5.jpeg','mainMedia');
// $source_file = Resize::img('6UXvEBkXx.png','mainMedia');
// $source_file = Resize::img('vqTSsyHqp.png','mainMedia');
// $source_file = Resize::img('bgY105ozr.jpeg','mainMedia');
// $source_file = Resize::img('l8KlzwG1n.jpeg','mainMedia'); mmm
$source_file = Resize::img('wXg13R9xb.jpeg','mainMedia');
// $source_file = Resize::img('MOHhp4Sdm.jpeg','mainMedia'); // todo mal aca
// $source_file = Resize::img('stCG7aovX.jpeg','mainMedia');
// $source_file = Resize::img('rxgANnPAV.jpeg','mainMedia');

echo '<img src="'.$source_file.'" width="70px"/>';

$image_helper = new ImageHelper($source_file);


$colors = $image_helper->getColorPalette(10);
?>
<table>
<?php
for ($i = 0; $i < sizeof($colors); $i++){
	echo "<tr><td bgcolor=".$colors[$i]." width=16 height=16></td>&nbsp;<td>".$colors[$i]."</td></tr>";
}
?>
</table>

<?php
list ($color1, $color2) = $image_helper->getDominantColors();
?>
<table>
	<?php
	echo "<tr><td bgcolor=".$color1." width=50 height=16>".$color1."</td><td bgcolor=".$color2." width=50 height=16>".$color2."</td></tr>";
	?>
</table>

<?php
$neutral = $image_helper->getOverallBrigthness();
echo 'Brillo: ' . $neutral;
?>