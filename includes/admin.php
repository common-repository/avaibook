<?php

//Evitar ejecucion indebida
if ( ! defined( 'WPINC' ) ) {
    die;
}



function avaibook_admin_tabs( $current = '1' ) {
    $tabs = array( '1' => __('Form 1','avaibook'), '2' => __('Form 2','avaibook'), '3' => __('Form 3','avaibook') );
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : "";
        echo "<a class='nav-tab$class' href='?page=avaibook&tab=$tab'>$name</a>";

    }
    echo '</h2>';
}?>


<h1>AvaiBook widgets</h1>
<p><b><?php _e("You can define three types of form. Each one can have a different configuration or presentation.","avaibook")?></b></p>


<?php $formNumber = (int) (isset($_GET["tab"])?sanitize_text_field($_GET["tab"]):1)?>
<?php $formNumber = $formNumber >=1 && $formNumber <=3 ? $formNumber : 1 ?>

<?php avaibook_admin_tabs($formNumber)?>
   
   


                <?php //for ($formNumber=1;$formNumber<4;$formNumber++){?>
                    
                    <a name="form<?php echo $formNumber?>"></a>
                    <div class="formBlk">
                    <?php if(isset($_POST["update".$formNumber]) && sanitize_text_field($_POST["update".$formNumber])=="1"){?>
        
                        <?php if ($avaibookOptions[$formNumber]["rentalType"]=="single" && !$avaibookOptions[$formNumber]["rentalId"]){ ?>
                            <div class="notice notice-error">
                                <p><?php _e('Rental Id is mandatory with "single" rental type.','avaibook')?></p>
                            </div>
                        <?php }elseif($avaibookOptions[$formNumber]["rentalType"]=="multiple" && !$avaibookOptions[$formNumber]["ownerId"]){?>
                            <div class="notice notice-error">
                                <p><?php _e('Owner Id is mandatory with "multiple" rental type.','avaibook')?></p>
                            </div>
                        <?php }else{ ?>
                            <div class="notice notice-success">
                                <p><?php _e('Configuration saved.','avaibook')?></p>
                            </div>
                        <?php }?>
        
                    <?php }?>

                    <h3><?php _e("Form","avaibook")?> <?php echo $formNumber?></h3>
                    <form method="post" id="avaibookForm<?php echo $formNumber?>" action="?page=avaibook&tab=<?php echo $formNumber?>">
                        <?php 
                        //create nonce. Security request from wordpress.org
                        wp_nonce_field( 'avaibook_update_'.$formNumber); ?>

                        <input type="hidden" name="update<?php echo $formNumber?>" value="1" />


                        <!--avaibook conf -->
                        <div class="postbox">
                            <h2 class="hndle ui-sortable-handle inside"><span><?php _e("AvaiBook configuration","avaibook")?></span></h2>
                            <p class="inside">
                                <?php _e("Choose the type of Booking Engine you wish to link, and fill in the requested parameters (those marked with * are mandatory and you will find their value in your private area of ​​AvaiBook)","avaibook")?>
                            </p>
                            <div class="inside">

                                            <div id="typeBlk<?php echo $formNumber?>" class="blk">
                                                <label for="rentalType<?php echo $formNumber?>"><?php _e("Booking Engine type","avaibook")?> <span class="mandatory">*</span>
                                                
                                                <select name="rentalType<?php echo $formNumber?>" id="rentalType<?php echo $formNumber?>" data-form-number="<?php echo $formNumber?>">
                                                    <option value="single" <?php echo $avaibookOptions[$formNumber]["rentalType"]=="single"?'selected="selected"':''?> ><?php _e("Single","avaibook")?></option>
                                                    <option value="multiple" <?php echo $avaibookOptions[$formNumber]["rentalType"]=="multiple"?'selected="selected"':''?>><?php _e("Multiple","avaibook")?></option>
                                                </select>
                                                <p class="help"><?php _e("Choose the type of booking engine you want to use. To a single accommodation or to all your accommodations","avaibook")?></p>
                                                </label>
                                            </div>

                                            <div id="singleBlk<?php echo $formNumber?>" class="typeBlk<?php echo $formNumber?>">
                                                    <div class="blk">
                                                        <label for="rentalId<?php echo $formNumber?>"><?php _e("Rental Id","avaibook")?> <span class="mandatory">*</span>
                                                        <input type="text" name="rentalId<?php echo $formNumber?>" id="rentalId<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["rentalId"]?>" />
                                                        <p class="help"><?php _e("This is the AvaiBook accommodation Id","avaibook")?></p>
                                                        </label>
                                                    </div>
                                                    <div class="blk">
                                                        <label for="singleReference<?php echo $formNumber?>"><?php _e("Reference","avaibook")?>
                                                        <input type="text" name="singleReference<?php echo $formNumber?>" id="singleReference<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["reference"]?>" />
                                                        <p class="help"><?php _e("(optional) the generated reserves will have this reference so that you can distinguish them","avaibook")?></p>
                                                        </label>
                                                    </div>
                                                
                                            </div>

                                            <div id="multipleBlk<?php echo $formNumber?>" class="typeBlk<?php echo $formNumber?>">
                                                
                                                <div class="blk">
                                                    <label for="ownerId<?php echo $formNumber?>">Owner Id <span class="mandatory">*</span>
                                                    <input type="text" name="ownerId<?php echo $formNumber?>" id="ownerId<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["ownerId"]?>" />
                                                    <p class="help"><?php _e("This is your customer id in AvaiBook","avaibook")?></p>
                                                    </label>
                                                </div>    
                                                
                                                <div class="blk">
                                                    <label for="multipleReference<?php echo $formNumber?>">Reference
                                                    <input type="text" name="multipleReference<?php echo $formNumber?>" id="multipleReference<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["reference"]?>" />
                                                    <p class="help"><?php _e("(optional) the generated reserves will have this reference so that you can distinguish them","avaibook")?></p>
                                                    </label>
                                                </div>
                                                    

                                                <div class="blk">   
                                                    <label for="showRentalUnits<?php echo $formNumber?>" class="secondaryOption"><input type="checkbox" name="showRentalUnits<?php echo $formNumber?>" id="showRentalUnits<?php echo $formNumber?>" value="1" <?php echo $avaibookOptions[$formNumber]["showRentalUnits"]?'checked=checked':""?> ><?php _e("Show rental units","avaibook")?></label>
                                                    <label for="showZones<?php echo $formNumber?>" class="secondaryOption"><input type="checkbox" name="showZones<?php echo $formNumber?>" id="showZones<?php echo $formNumber?>" value="1" <?php echo $avaibookOptions[$formNumber]["showZones"]?'checked=checked':""?> ><?php _e("Show zones","avaibook")?></label>
                                                    <label for="showPeople<?php echo $formNumber?>" class="secondaryOption"><input type="checkbox" name="showPeople<?php echo $formNumber?>" id="showPeople<?php echo $formNumber?>" value="1" <?php echo $avaibookOptions[$formNumber]["showPeople"]?'checked=checked':""?> ><?php _e("Show people","avaibook")?></label>
                                                    <p class="help"><?php _e("Behavior in the booking engine","avaibook")?></p>
                                                </div>
                                            </div>
                            </div>
                        </div>
                        
                        <!--display options -->
                        <div class="postbox">
                            <h2 class="hndle ui-sortable-handle inside"><span><?php _e("Display options","avaibook")?></span></h2>
                            <p class="inside">
                            <?php _e("Choose how you want your widget to be and what colors you want it to have. If you do not mark any of the options below your widget will only be a button.","avaibook")?></p>
                            <div class="inside">
                                    <div class="blk">
                                        <label for="title<?php echo $formNumber?>"><?php _e('title','avaibook')?><input  name="title<?php echo $formNumber?>" id="title<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["title"]?>"  />
                                        <p class="help"><?php _e('This text title will be showed in your form','avaibook')?></p>
                                        </label>
                                    </div>
                                    <div class="blk">
                                        <label for="requestDates<?php echo $formNumber?>"><input type="checkbox" name="requestDates<?php echo $formNumber?>" id="requestDates<?php echo $formNumber?>" value="1" <?php echo $avaibookOptions[$formNumber]["requestDates"]?'checked=checked':""?> >
                                        <?php _e('Request dates','avaibook')?>
                                        <p class="help"><?php _e("show dates request's fields",'avaibook')?></p>
                                        </label>
                                    </div>
                                    <div class="blk">
                                        
                                        <label for="requestGuestNumber<?php echo $formNumber?>">
                                        <input type="checkbox" name="requestGuestNumber<?php echo $formNumber?>" id="requestGuestNumber<?php echo $formNumber?>" value="1" <?php echo $avaibookOptions[$formNumber]["requestGuestNumber"]?'checked=checked':""?>>
                                        <?php _e("Request guest number",'avaibook')?>
                                        <p class="help"><?php _e("show guest's numbers field",'avaibook')?></p>
                                        </label>
                                    </div>
                                    <!-- color selectors -->
                                    <h4><?php _e("Colour settings",'avaibook')?></h4>
                                    <p><?php _e("Set empty for keep your default style.",'avaibook')?></p>
                                    <div id="colorSelectors<?php echo $formNumber?>" class="colorSelectors">
                                        <div class="backgroundColorSelectorBlk">       
                                                    <label for="backgroundColor<?php echo $formNumber?>"><span id="backgroundColorView<?php echo $formNumber?>" class="colorViewer"></span><?php _e("Background colour",'avaibook')?></label>
                                                    <br/>
                                                    <input type="text" name="backgroundColor<?php echo $formNumber?>" id="backgroundColor<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["backgroundColor"]?>" class="backgroundColor" />
                                                    <div id="backgroundColorSelector<?php echo $formNumber?>" class="backgroundColorSelector">
                                                        <div id="backgroundPicker<?php echo $formNumber?>" class="backgroundPicker"></div>
                                                        <div id="backgroundSlide<?php echo $formNumber?>" class="backgroundSlide"></div>
                                                    </div>    
                                        </div>
                                        <div class="mainColorSelectorBlk">
                                                    <label for="mainColor<?php echo $formNumber?>"><span id="mainColorView<?php echo $formNumber?>" class="colorViewer"></span><?php _e("Main colour",'avaibook')?></label>
                                                    <br/>
                                                    <input type="text" name="mainColor<?php echo $formNumber?>" id="mainColor<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["mainColor"]?>" class="mainColor" />
                                                    <div id="mainColorSelector<?php echo $formNumber?>" class="mainColorSelector">
                                                        <div id="mainPicker<?php echo $formNumber?>" class="mainPicker"></div>
                                                        <div id="mainSlide<?php echo $formNumber?>" class="mainSlide"></div>
                                                    </div>    
                                        </div>
                                        <div class="textColorSelectorBlk">
                                                    <label for="textColor<?php echo $formNumber?>"><span id="textColorView<?php echo $formNumber?>" class="colorViewer"></span><?php _e("Text colour",'avaibook')?></label>
                                                    <br/>
                                                    <input type="text" name="textColor<?php echo $formNumber?>" id="textColor<?php echo $formNumber?>" value="<?php echo $avaibookOptions[$formNumber]["textColor"]?>" class="textColor" />
                                                    <div id="textColorSelector<?php echo $formNumber?>" class="textColorSelector">
                                                        <div id="textPicker<?php echo $formNumber?>" class="textPicker"></div>
                                                        <div id="textSlide<?php echo $formNumber?>" class="textSlide"></div>
                                                    </div>    
                                        </div>
                                    </div>
                            </div>
                        </div>



                        <input type="submit" name="submit<?php echo $formNumber?>" id="submit<?php echo $formNumber?>" class="button button-primary" value="<?php _e("Save changes",'avaibook')?>">

                        <br/><br/>

                        <div class="postbox">
                            <h2 class="hndle ui-sortable-handle inside"><span><?php _e("Options",'avaibook')?></span></h2>
                            <div class="inside">
                                <h3><?php _e("You can use this shortcode",'avaibook')?></h3>
                                <code>[avaibook<?php echo $formNumber?>]</code>
                                <p><?php _e("Only copy this code, and put it where you want in your post or pages.",'avaibook')?></p>

                                <h3><?php _e("Or you can use our widget",'avaibook')?></h3>
                                
                                <p><?php echo sprintf( __( 'Go to <a href="%s">widgets section</a> and drag our widget "avaibook%s" where you want.', 'avaibook' ), admin_url( 'widgets.php'),$formNumber );?></p>
                            </div>
                        </div>



                    </form>
                    </div>

                    

                <?php //}//end for?>

  

