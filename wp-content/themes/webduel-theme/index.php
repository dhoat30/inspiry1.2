<?php get_header();?>
   
    
   <?php 
   

  
  
   /*
    $curl = curl_init();
    $url = "https://api.bigcommerce.com/stores/zuh5fsa2r/v3/catalog/products/113";
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "x-auth-client: 23k00mq7lb0d2k461j5wr6vh5u9xaur",
        "x-auth-token: 8v5clxzj8a3xmu9fjp0g093t8x4ktco"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      //Only show errors while testing
      //echo "cURL Error #:" . $err;
    } else {
      //The API returns data in JSON format, so first convert that to an array of data objects
      $responseObj = json_decode($response);
      
      $result = (array) $responseObj;
      $resultProduct = (array) $result['data'];
    print_r($resultProduct);
     
     
    }
    */
    

    while(have_posts()){
        the_post(); 
        ?>
        <h1><?php the_title( 'the title ');?></h1>
        <div><?php the_content();?></div>
        <?php
    }

   
    
   ?>
<h2>Fabric Calculator</h2>

<!--
<form action="" id="cal-form">

    <label for="fabric">Choose Fabric Type</label>
    <select id="fabric-type" name="fabric-type">
      <option value="inverted">Inverted Pleat</option>
      <option value="pencil">Pencil Pleat</option>
      <option value="french">French Pleat</option>
      <option value="new-york">New York Pleat</option>
      <option value="wave">Wave Pleat</option>
    </select>
    <br><br>

  <label for="fabric">Fabric Width</label>
  <input id="fabric-width" type="text" name="fabric" >
  <br><br>

  <label for="track">Track Length</label>
  <input id="track-length" type="text"  name="track" required>
  <br><br>

  <label for="track">Curtain Length (Drop)</label>
  <input id="curtain-length" type="text"  name="drop" required>
  <br><br>

  <label for="fabric">Pattern Repeat? </label>
  <select id="pattern" name="pattern-type">
      <option value="option">Choose Option</option>
      <option value="yes">Yes</option>
      <option value="no">no</option>
  </select>
  <br><br>

  <label for="pattern-value-label-horizontal" class="form-hidden-field">Patter Repeat Horizontal Value</label>
  <input id="pattern-value-hr" type="text" name="pattern-hr" class="form-hidden-field">
  <br>

  <br>
  <label for="pattern-value-label-vertical" class="form-hidden-field">Patter Repeat Vertical Value</label>
  <input id="pattern-value-vr" type="text" name="pattern-vr" class="form-hidden-field">
  <br><br>

  <button id="f-button" type="submit">Calculate</button>
  <h5 id='calculated-data'></h5>

</form>
<button class="button-test">CLick</button>
-->

<div class="sl-modal-content fabric-rolls">
        <section class="contentContainer container-flex">
            <form novalidate="novalidate">
                <fieldset>
                    <div class="form-group">
                        <h3><span>1</span> <em>Please</em> check the dimensions of your fabric</h3>
                            <p class="small">we've started you off with our standard sizes below:</p>
                        <div class="row">
                            <div class="small-6 medium-6 large-6 columns">
                                <label id="fabric-width-label">Fabric width in cm</label>
                                <input id="fabric-width" type="number" value="138">
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-6 medium-6 large-6 columns">
                                <label id="fabric-match-label">Pattern Match</label>
                                <select id="fabric-pattern-match" class="">
                                    <option value="" selected="">No Pattern Match</option>
                                    <option value="Straight Match">Straight Match</option>
                                    <option value="Quarter Drop Match">Quarter Drop Match</option>
                                    <option value="Third Drop Match">Third Drop Match</option>
                                    <option value="Half Drop Match">Half Drop Match</option>
                                    <option value="Random Match">Random Match</option>
                                    <option value="Reverse Hang Alternative Lengths">Reverse Hang Alternative Lengths</option>
                                </select>
                            </div>
                            <div class="small-6 medium-6 large-6 columns patternRpt">
                                <label id="fabric-repeat-label">Pattern Repeat in cm</label>
                                <input id="fabric-repeat" type="number" value="0" style="padding:11px;">
                            </div>
                            <input type="hidden" id="loadedPatternMatch" value="">
                            <input type="hidden" id="Ssp" value="0">
                        </div>
                    </div>

                    <div class="form-group unit-selection">

                        <h3><span>2</span>select a unit of measurement</h3>
                        <div class="row">
                            <div>
                                <input name="units" id="input-centimetres" type="radio" value="centimetres" checked="checked">
                                <label class="radio" for="input-centimetres"> Centimetres </label>
                            </div>
                            <div>
                                <input name="units" id="input-inches" type="radio" value="inches">
                                <label class="radio" for="input-inches"> Inches </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group unit-selector">
                        <h3><span>3</span>add your curtain/blind size</h3>
                        <div id="fabric-group"><div class="wall metres" data-wall="metres_wall_">
    <div class="row">
        <div class="small-6 medium-6 large-6 columns">
            <label>Width in centimetres</label>
            <input class=" width roll" type="number" placeholder="width in centimetres" name="measure">
        </div>
        <div class="small-6 medium-6 large-6 columns">
            <label>Height in centimetres</label>
            <input class="height roll" type="number" placeholder="height in centimetres" name="measure">
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // retrieve previously entered calc measurements
        var width=$.cookie("calcWidth")*100; 
        var height=$.cookie("calcHeight")*100;

        // populate calculator
        if(width!=null||height!=null) {
            $('input.width.roll').val(width.toFixed(0));
            $('input.height.roll').val(height.toFixed(0));
        }

    });
</script></div>
                        <p id="error-message" class="error-message"></p>
                    </div>

                    <div class="form-group style-selection">
                        <h3><span>4</span>Choose your style</h3>
                        <div class="row style-selector">
                            <div>
                                <input type="radio" name="style" value="curtains" checked="">
                                <label class="radio" for="curtains"> Curtains </label>
                            </div>
                            <div>
                                <input type="radio" name="style" value="blinds">
                                <label class="radio" for="blinds"> Blinds </label>
                            </div>
                        </div>
                        <div id="curtain-styles">
                            <div class="row">
                                <div class="small-6 medium-6 large-6 columns">
                                    <label>Pair or Single</label>
                                    <select id="curtainType">
                                        <option value="Pair">Pair</option>
                                        <option value="Single">Single</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="small-6 medium-6 large-6 columns">
                                    <label>Heading Type</label>
                                    <select id="heading-type">
                                        <option value="">Please select</option>
                                        <option value="Pencil Pleat">Pencil Pleat</option>
                                        <option value="Gathered Heading">Gathered Heading</option>
                                        <option value="Triple Pleat">Triple Pleat</option>
                                        <option value="Goblet Heading">Goblet Heading</option>
                                        <option value="Eyelets">Eyelets</option>
                                        <option value="Tab Tops">Tab Tops</option>
                                    </select>
                                </div>

                                <div class="small-6 medium-6 large-6 columns fullness hide">
                                    <label>Fullness</label>
                                    <select id="fullness">
                                        <option value="" class="" select="selected">select</option>
                                        <option value="1.9" class="pencilpleat">1.9 (less full)</option>
                                        <option value="2.2" class="pencilpleat">2.2x (normal)</option>
                                        <option value="2.5" class="pencilpleat">2.5x (more full)</option>
                                        <option value="1.7" class="gatheredheading">1.7x (less full)</option>
                                        <option value="2.2" class="gatheredheading">2.2x (normal)</option>
                                        <option value="2.5" class="gatheredheading">2.5x (more full)</option>
                                        <option value="2.1" class="triplepleat">2.1x (less full)</option>
                                        <option value="2.3" class="triplepleat">2.3x (normal)</option>
                                        <option value="2.5" class="triplepleat">2.5x (more full)</option>
                                        <option value="1.9" class="gobletheading">1.9x (less full)</option>
                                        <option value="2.2" class="gobletheading">2.2x (normal)</option>
                                        <option value="2.5" class="gobletheading">2.5x (more full)</option>
                                        <option value="1.3" class="eyelets">1.3x (less full)</option>
                                        <option value="1.7" class="eyelets">1.7x (normal)</option>
                                        <option value="2.2" class="eyelets">2.2x (more full)</option>
                                        <option value="1.3" class="tabtops">1.3x (less full)</option>
                                        <option value="1.7" class="tabtops">1.7x (normal)</option>
                                        <option value="2.2" class="tabtops">2.2x (more full)</option>
                                    </select>
                                </div>

                                <p id="error-message-2" class="error-message"></p>
                            </div>
                        </div>
                        <div id="blind-styles" class="hide">
                            <div class="row">
                                <div class="small-6 medium-6 large-6 columns">
                                    <label id="">Blind Style</label>
                                    <select id="" class="">
                                        <option value="Roman Blind">Roman Blind</option>
                                        <option value="Roller Blind">Roller Blind</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="button-holder">
                        <button id="calculate-fabric-btn" type="submit" class="actionButton"><span class="fa fa-calculator" aria-hidden="true"></span>Calculate Fabric Needed</button>
                        <button id="reset-btn" type="button" class="secondaryButton"><span class="fa fa-refresh" aria-hidden="true"></span>Reset</button>
                    </div>
                </fieldset>
            </form>
        </section>

        <section id="result">
            <div id="roll-result">
                <p class="default-text">*Enter your fabric and window dimensions to calculate how much fabric your project will require.</p>

                <p class="result-text">
                    *You will need approximately <actual>0</actual>&nbsp;metres of fabric
                </p>
            </div>
            <hr>
            <div id="disclaimer">
                <p>*This calculator provides an approximate recommendation only. Style Library advise that you always consult your retailer before ordering fabric as we cannot be held responsible for any incorrect quantities of fabric ordered.</p>
            </div>
        </section>
    </div>
    <script>var fabricWidthLabel = $('#fabric-width-label');
    var fabricHeightLabel = $('#fabric-height-label');
    var units = $('input[name=units]:checked').val();
    var winWidth = window.innerWidth;
    var fabricModal = $('#FabricCalculatorModal').length;

    $("#fabric-pattern-match").val($("#loadedPatternMatch").val());

    $(document).ready(function () {

        loadfabric();
        UpdateUnits();
        CalculatefabricNeeded();
        Reset();
    });


    function loadfabric() {

        $.ajax({
            url: '/calculator/MeasurementSnippet/?viewName=' + units,
            type: 'GET',
            dataType: 'html'
        })
         .success(function (result) {
             $("#fabric-group").html(result);
             UpdatefabricId();
         });
    }

    function UpdateUnits() {
        $('input[name=units]').change(function () {
            units = $('input[name=units]:checked').val();
            changefabric();
        });
    }

    function changefabric() {

        $.ajax({
            url: '/calculator/MeasurementSnippet/?viewName=' + units,
            type: 'GET',
            dataType: 'html',
            success: function (result) {
                $('#fabric-group').empty().append(result);
                UpdatefabricId();
            },
            error: function () {
                //todo 
                alert('failed');
            }
        });
    }

    function fabricCount() {
        return $("#fabric-group div").length;
    }

    function UpdatefabricId() {
        var count = $("#fabric-group > div").length;
        var lastfabric = $("#fabric-group > div:last-child");
        var id = lastfabric.attr('data-fabric');
        var newId = id + count;
        $("#fabric-group > div:last-child").attr('data-fabric', newId);
    }

    function CalculatefabricNeeded(e) {
        $('#calculate-fabric-btn').on('click', function (e) {
            e.preventDefault();

            // reset errors
            $('.has-error').removeClass('has-error');

            var check = ValidateInputs();
            var check2 = ValidateFabric();
            var style = $('input[name=style]:checked').val();


            // validate curtain inputs
            if(style=='curtains') {
                if(check>=$('input[name=measure]').length&&check2>=1&&validateHeading()==true&&validateFullness()==true) {
                    fabricCalc();
                }
                else {
                    if(check<$('input[name=measure]').length) {
                        $('#error-message').text('Please enter measurements').addClass('message error');
                    }
                }
            }

            // validate blind inputs
            else if(style=='blinds') {
                if(check>=$('input[name=measure]').length&&check2>=1) {
                    fabricCalc();
                }
                else {
                    if(check<$('input[name=measure]').length) {
                        $('#error-message').text('Please enter measurements').addClass('message error');
                    }
                }
            }

            // calculate fabric
            function fabricCalc() {
                    $('#error-message, #error-message-2').empty().removeClass('message error');

                    var fabricObject=CreateJsonObject(units);

                    //Removes the default text
                    $(".default-text").replaceWith($('.result-text').fadeIn());
                    // scroll result into view
                    $('html,body,#sl-modal').animate({
                        scrollTop: $(".result-text").offset().top
                    });


                    $.ajax({
                        url: '/calculator/CalculateFabric',
                        type: 'POST',
                        data: JSON.stringify(fabricObject),
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function(result) {

                            if(result.TotalFabricRequired) {
                                $('actual').empty().append(result.TotalFabricRequired);
                            }

                            if(result.EstimatedPrice!==null&&result.EstimatedPrice!=="£0.00") {
                                $('totalprice').empty().append(result.EstimatedPrice);
                                $('#pricing-info').show();
                            }
                        },
                        error: function() {
                            alert('failed to talk to server');
                        }
                    });
                }

        });
    }


    function ValidateInputs() {

        var int = 0;
        $('input[name=measure]').each(function () {
            if ($.isNumeric($(this).val())) {
                int++;
                $(this).removeClass('has-error');
            }
            else {
                int--;
                $(this).addClass('has-error');
            }
        });

        return int;
    };

    function validateHeading() {


            var $this = $('#heading-type option:selected');

            if($this.val()=="") {
                $('#heading-type').addClass('has-error');
                $('#error-message-2').text('Please select Heading Type').addClass('message error');
                return false;
            }
            else {
                $('#heading-type').removeClass('has-error');
            }

            return true;

    }

    function validateFullness() {


            var $this=$('#fullness option:selected');

            if($this.val()=="") {
                $('#fullness').addClass('has-error');
                $('#error-message-2').text('Please select Fullness').addClass('message error');
                return false;
            }
            else {
                $('#fullnesss').removeClass('has-error');
            }

            return true;

    }

    function ValidateFabric() {

        var int = 0;
        if ($.isNumeric($('#fabric-width').val())) {
            int++;
            $('#fabric-width').removeClass('has-error');
        }
        else {
            int--;
            $('#fabric-width').addClass('has-error');
        }

        return int;

    }

    function Reset(e) {
        $('#reset-btn').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('.fabric').val('');
            $('#fabric-group').empty();
            $('actual').empty().append("0 fabric");
            $('approx').empty().append("0 fabric");
            loadfabric();
            //hides the price on reset
            $('#pricing-info').hide();
            return false;
        });
    }

    function CreateJsonObject() {  
        var window;

        if( units === "inches") {
            window = getWindowInches();
        }
        else if(units==="centimetres") {
            window=getWindowCentimetres();
        }

        return {
            "Units": units,
            "Singleorpair": $('#curtainType option:selected').val(),
            "usage": $("input[name='style']:checked").val(),
            "SSp": $('#Ssp').val(),
            "Fullness": $('#fullness option:selected').val(),
            "RollDimensions": {
                "Width": ($('#fabric-width').val()),
                "Height": $('#fabric-height').val(),
                "PatternMatch": $("#fabric-pattern-match").val(),
                "PatternRepeat": $("#fabric-repeat").val()
            },
            "window": window,
        };
    }

    function getWindowCentimetres() {
        var object={ "Width": null,"Height": null };

        $('#fabric-group > div').each(function() {
            var width=$(this).find('.width').val();
            var height=$(this).find('.height').val();

            var widthCm=width/100;
            var heightCm=height/100;

            object.Width = widthCm; 
            object.Height = heightCm; 

            // save window measurements to cookie
            $.cookie("calcWidth",widthCm);
            $.cookie("calcHeight",heightCm);
            // update inches measurement
            $.cookie("calcWidthInches",(Math.floor(widthCm*39.370)));
            $.cookie("calcHeightInches",(Math.floor(heightCm*39.370)));

        });

        return object;
    }

    function getWindowInches() {
        var object = { "Width": null, "Height": null };

        $('#fabric-group > div').each(function () {
            var width = $(this).find('.width').val();
            var height = $(this).find('.height').val();

            object.Width = width * 0.0254;
            object.Height = height * 0.0254;

            // save window measurements to cookie
            $.cookie("calcWidthInches",width);
            $.cookie("calcHeightInches",height);
            // update metre measurement
            $.cookie("calcWidth",((width/39.370).toFixed(2)));
            $.cookie("calcHeight",((height/39.370).toFixed(2)));

        });

        return object;
    }


    // style selection 

        // curtains or blinds   
        $("input[name='style']:radio")
            .change(function() {
                $("#curtain-styles").toggle($(this).val()=="curtains");
                $("#blind-styles").toggle($(this).val()=="blinds");
        });

        // pattern repeat
        $('#fabric-pattern-match')
            .change(function(){
                $('.patternRpt').toggle($(this).val()!=""); // show/hide pattern rpt input
                if($(this).val()=="") {
                    $('#fabric-repeat').val("0"); // reset pattern rpt value
                };
            });

        // headings and fullness
        $("#heading-type")
            .change(function() {

                $('.fullness').toggle($(this).val()!=""); // show/hide fullness
                $("#fullness").val(''); // reset select value to null

                // toggle fullness values for selected heading type
                $(".pencilpleat").toggle($(this).val()=="Pencil Pleat");
                $(".gatheredheading").toggle($(this).val()=="Gathered Heading");
                $(".triplepleat").toggle($(this).val()=="Triple Pleat");
                $(".gobletheading").toggle($(this).val()=="Goblet Heading");
                $(".eyelets").toggle($(this).val()=="Eyelets");
                $(".tabtops").toggle($(this).val()=="Tab Tops");

            });


</script>

<?php get_footer();?>