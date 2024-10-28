<?php
//Evitar ejecucion indebida
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>

<style>
<?php for($formNum=1;$formNum <=3; $formNum++){?>
    <?php if (isset($avaibookOptions[$formNum])){?>
        <?php $avaibookFormOption = $avaibookOptions[$formNum]?>
    .avaibookSearch<?php echo $formNum?>{
        
        background:<?php echo $avaibookFormOption["backgroundColor"]?$avaibookFormOption["backgroundColor"]:"transparent"?>;
        
    }

    <?php if ($avaibookFormOption["textColor"]){ ?>
    .avaibookSearch<?php echo $formNum?> h3,.avaibookSearch<?php echo $formNum?> label{
        color:<?php echo $avaibookFormOption["textColor"] ?> !important;
    }
    <?php }?>
    <?php if ($avaibookFormOption["mainColor"]){?>
        .avaibookSearch<?php echo $formNum?> input{
            border: 1px solid <?php echo hex2rgba($avaibookFormOption["mainColor"],0.7)?>;
        }
        .avaibookSearch<?php echo $formNum?> input:focus{
            border: 1px solid <?php echo hex2rgba($avaibookFormOption["mainColor"])?>;
        }
        .avaibookSearch<?php echo $formNum?> form > button{
        
        background:<?php echo hex2rgba($avaibookFormOption["mainColor"],0.9)?> !important;
        border: none !important;
        margin-top: 22px !important;

        }
        .avaibookSearch<?php echo $formNum?> form > button:hover{
            background:<?php echo hex2rgba($avaibookFormOption["mainColor"],1)?> !important;
        }
    <?php }?>
    <?php }?>
<?php }?>
.avaibookSearch{
        font-size:15px;
        padding:10px;
        margin-bottom:3em;
    }
.avaibookSearch form{display:flex;justify-content:space-around;flex-wrap:wrap}
.avaibookSearch form > *{box-sizing:border-box;width:200px;margin-bottom:10px}
.avaibookSearch form > label > input,.avaibookSearch  form > button{height:40px;box-sizing:border-box}
.avaibookSearch form > button{
    padding:5px 0 0 0;
    margin-top:24px;
}
.lightpick__previous-action, .lightpick__next-action, .lightpick__close-action {
    padding: 8px 18px 26px 18px !important;
    color:black !important;
}
/*fix mobile problems datepicker*/
@media (max-width: 400px) {
    .lightpick{
        width:100% !important;
        left:0 !important;
    }
    .lightpick__month{width:100% !important}
}
</style>


