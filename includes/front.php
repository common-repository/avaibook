<?php
//Evitar ejecucion indebida
if ( ! defined( 'WPINC' ) ) {
    die;
}

$formAction = AVAIBOOK_BASE_URL . ($avaibookOptions["rentalType"] == "multiple" ? "/reservas/listado.php" : "/reservas/nueva_reserva.php");
?>
<div class="avaibookSearch avaibookSearch<?php echo $formNumber?>">
    <?php if ($avaibookOptions["title"]){?>
    <h3><?php echo esc_html($avaibookOptions["title"])?></h3>
    <?php } ?>
    <form  action="<?php echo $formAction?>" target="_blank">
        <?php if($avaibookOptions["requestDates"]){ ?>
        <label for="avaibookArriveDate<?php echo $htmlId?>">
            <?php _e("Arrive date","avaibook")?>
            <input type="text"  class="avaibookDatePicker init" placeholder="" id="avaibookArriveDateCalendar<?php echo $htmlId?>" />
        </label>
        <input type = "hidden" name="f_ini" class="avaibookDateInputInit" id="avaibookArriveDateInput<?php echo $htmlId?>" value="" />
        <label for="avaibookDepartDate<?php echo $htmlId?>">
            <?php _e("Departure date","avaibook")?>
            <input type="text"  class="avaibookDatePicker end" placeholder="" id="avaibookDepartDateCalendar<?php echo $htmlId?>" />
        </label>
        <input type = "hidden" name="f_fin" class="avaibookDateInputEnd" id="avaibookDepartDateInput<?php echo $htmlId?>" value="" />
        <?php }?>

        <?php if($avaibookOptions["requestGuestNumber"]){ ?>
        <label for="avaibookGuests">
            <?php _e("Guest Num.","avaibook")?>
            <input type="text" name="capacidad" placeholder="<?php _e("Guest Num.","avaibook")?>" />
        </label>
        <?php }?>

        <button><?php _e("search","avaibook")?></button>
        
        <?php if($avaibookOptions["rentalType"]=="multiple"){?>
            <input type="hidden" name="cod_propietario" value="<?php echo $avaibookOptions["ownerId"]?>" />
        <?php }?>

        <?php if($avaibookOptions["rentalType"]=="single"){?>
            <input type="hidden" name="cod_alojamiento" value="<?php echo $avaibookOptions["rentalId"]?>" />
        <?php }?>

        <?php if($avaibookOptions["reference"]){?>
            <input type="hidden" name="referencia_propietario" value="<?php echo $avaibookOptions["reference"]?>" />
        <?php }?>

        <?php if ($avaibookOptions["showRentalUnits"]){?>
            <input type="hidden" name="unidades" value="1" />
        <?php }?>

        <?php if ($avaibookOptions["showZones"]){?>
            <input type="hidden" name="filter_zone" value="1" />
        <?php }?>

        <?php if ($avaibookOptions["showPeople"]){?>
            <input type="hidden" name="filter_persons" value="1" />
        <?php }?>

            <input type="hidden" name="lang" value="<?php echo getAvaibookLanguaje()?>" />
        

    </form>
</div>

