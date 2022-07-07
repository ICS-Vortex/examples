import React from 'react';

const TournamentCouponTextEn = () => {
    return (
        <div>
            Each participant of the Shadow's Trophy Tournament Qualification can get a discount
            in the VIRPIL Controls webstore. <br/>

            To do this, you need to fly the Shadow's Trophy Race Track on any of the VPC
            Helicopter Race Servers. <br/>

            After that, there are two options for action: <br/><br/>
            <hr/>
            1. By requesting an email. <br/>
            - In the server chat, type the command: <b className="text-danger">vpc email
            address@domain.com</b> <br/>
            (example: vpc email ShadowsTrophy@gmail.com)
            - Receive an email containing a link to the tournament page and your UCID. <br/>
            - Follow the link, enter your UCID and select the VIRPIL Controls webstore in
            accordance with your region *: <br/>
            <ul>
                <li>-- EAEU **</li>
                <li>-- Rest of the world</li>
            </ul>
            - Click the “Get coupon” button. <br/> <br/>
            <hr/>
            2. By requesting your UCID. <br/>
            - In the server chat, type the command: <b className="text-danger">vpc ucid</b>
            <br/>
            - Get your identifier in the chat - UCID. (It will be visible only to you) <br/>
            - Go to the tournament page “5% Discount”. <br/>
            - Fill in the fields: <br/>
            <ul>
                <li>-- UCID</li>
                <li>-- Store Region *</li>
                <li>-- Email address.</li>
            </ul>
            - Click the "Get coupon" button. <br/> <br/>
            <hr/>

            We will need your email address to send the coupon. <br/>

            This code will be valid for 3 (three) months from the date of issue. The coupon is
            intended for 1 (one) purchase in VIRPIL Controls Stores, regardless of the
            completeness of the coupon amount.
            <br/><br/>
            You can transfer this coupon, however the following restrictions apply: <br/>
            <ol>
                <li>Two or more coupon codes cannot be used in one purchase.</li>
                <li>The transfer of coupons or discount codes is possible only within one of the
                    VIRPIL Controls Stores (EAEU or Rest of the World).
                </li>
            </ol>

            * Please note: the coupon will be valid only in the specified store and will be sent
            to the specified email address within 24 hours. <br/> <br/>
            ** The EAEU Shop operates in the following countries: <br/>
            <span className="text-danger">★</span> Republic of Belarus <br/>
            <span className="text-danger">★</span> Russian Federation <br/>
            <span className="text-danger">★</span> Republic of Kazakhstan <br/>
            <span className="text-danger">★</span> Kyrgyz Republic <br/>
            <span className="text-danger">★</span> Republic of Tajikistan <br/>

        </div>
    );
};

export default TournamentCouponTextEn;