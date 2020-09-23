<?php
get_header(); 
    while(have_posts()){ 
        the_post(); 
        ?>
        <style>
            /* Backgorund Images */
   
                .slide:first-child {
                    background: url('https://source.unsplash.com/RyRpq9SUwAU/1600x900') no-repeat
                    center top/cover;
                }
                .slide:nth-child(2) {
                    background: url('https://source.unsplash.com/BeOW_PJjA0w/1600x900') no-repeat
                    center top/cover;
                }
                .slide:nth-child(3) {
                    background: url('https://source.unsplash.com/TMOeGZw9NY4/1600x900') no-repeat
                    center top/cover;
                }
                .slide:nth-child(4) {
                    background: url('https://source.unsplash.com/yXpA_eCbtzI/1600x900') no-repeat
                    center top/cover;
                }
                .slide:nth-child(5) {
                    background: url('https://source.unsplash.com/ULmaQh9Gvbg/1600x900') no-repeat
                    center top/cover;
                } 
                .slide:nth-child(6)  {
                    background: url('https://source.unsplash.com/ggZuL3BTSJU/1600x900') no-repeat
                    center center/cover;
                }
        </style>
                <div class="slider">
                    <div class="slide current">
                        <div class="content">
                            <!--
                        <h1>Slide One</h1>
                        <p>
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit hic
                            maxime, voluptatibus labore doloremque vero!
                        </p>
                        -->
                        </div>
                    </div>
                    <div class="slide">
                        
                        <div class="content">
                        <!--  
                        <h1>Slide Two</h1>
                        <p>
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit hic
                            maxime, voluptatibus labore doloremque vero!
                        </p>
                        -->
                        </div>
                    </div>
                    <div class="slide">
                        <div class="content">
                            <!--
                        <h1>Slide Three</h1>
                        <p>
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit hic
                            maxime, voluptatibus labore doloremque vero!
                        </p>
                        -->
                        </div>
                    </div>
                    
                    </div>
                    <div class="buttons">
                    <button id="prev"><i class="fas fa-arrow-left"></i></button>
                    <button id="next"><i class="fas fa-arrow-right"></i></button>
                </div>

        <div class="body-contaienr inspiry-trade">
            <div class="row-container">
                <h1 class="center-align section-ft-size playfair-fonts"><?php the_title();?></h1>
                <h2 class="work-sans-fonts center-align regular dark-grey column-s-font">
                    Inspiry Trade is your online hub for all interior design and building projects.

                    We provide a space for you and your business to create innovate and grow. 
                    Reach more people and get networking with like minded individuals all through Inspiry Trade
                </h2>
                <a class="bc-btn bc-btn--register trade-register-button" href='<?php echo get_home_url(). "/register-2" ?>'> Create A Trade Account</a>

            </div>

            <div class="row-container card-row">
                <div class="card-title">
                    <h3 class="section-ft-size work-sans-fonts regular"> Home Design & Build</h3>
                    <div class="underline-dg"></div>
                </div>
                <div class="card-content">
                    <ul>
                        <li>Architects</li>
                        <li>Construction</li>
                        <li>Designers</li>
                        <li>Painters</li>
                        <li>Plumbers</li>
                        <li>Joinery & Cabinet Makers</li>
                        <li>Wardrobe Makers</li>
                        <li>Roofers</li>
                        <li>Heating & Air Conditioning</li>
                    </ul>
                    <ul>
                        <li>Door Installers</li>
                        <li>Solar Energy Services</li>
                        <li>Tiler & Stone Mason</li>
                        <li>Fireplace Installers</li>
                        <li>Cladding & Exterior Contractors</li>
                        <li>Electricians</li>
                        <li>Floor Coverings & Carpets</li>
                        <li>Window Dressing & Treatments</li>
                    </ul>
                    <ul>
                        <li>Garage Door & Gate services</li>
                        <li>Wallpaper Hanger</li>
                        <li>Wallpaper Treatments</li>
                        <li>Water supply & Tank Services </li>
                        <li>Wifi Installers</li>
                        <li>Upholsterers</li>
                        <li>Insulation</li>
                    </ul>
                </div>
            </div>

            <div class="row-container card-row">
                <div class="card-title">
                    <h3 class="section-ft-size work-sans-fonts regular"> Outdoors & Gardens</h3>
                    <div class="underline-dg"></div>
                </div>
                <div class="card-content">
                   <ul>
                       <li>Driveways & Paving</li>
                       <li>Fencing & Gates</li>
                       <li>Gardner</li>
                       <li>Lawn & Sprinklers</li>
                       <li>Outdoor Lighting</li>
                   </ul>
                   <ul>
                       <li>Pool & Spas</li>
                       <li>Pool Lighting</li>
                       <li>Landscape Architects</li>
                       <li>Landscape Designers</li>
                       <li>Waste Removal</li>
                   </ul>
                   <ul>
                       <li>Hedge Trimming</li>
                       <li>Tree Trimming</li>
                       <li>Mulch Installation</li>
                       <li>Weed Removal</li>
                   </ul>
                </div>
            </div>

            <div class="row-container card-row">
                <div class="card-title">
                    <h3 class="section-ft-size work-sans-fonts regular"> Home Services & Maintenance</h3>
                    <div class="underline-dg"></div>
                </div>
                <div class="card-content">
                   <ul>
                       <li>Arborists & Tree Services</li>
                       <li>Gutter cleaning</li>
                       <li>House washing</li>
                       <li>Deck Cleaning</li>
                   </ul>
                   <ul>
                       <li>Window Cleaning</li>
                       <li>Roof Washing</li>
                       <li>Insect specialists</li>
                       <li>Data Cabling</li>
                   </ul>
                   <ul>
                       <li>Carpet cleaners</li>
                       <li>Wood Floor polishing</li>
                       <li>Floor Cleaners</li>
                       <li>House Cleaning</li>
                   </ul>
                </div>
            </div>


         </div>

        <?php

    }
get_footer(); 
?>