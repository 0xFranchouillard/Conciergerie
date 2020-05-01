<?php

define('_HOME','Home');
define('_SERVICES','Our Servicies');
define('_ABONNEMENTS','Subscription');
define('_CONTACT','Contact Us');
define('_PROFIL','My Profile');
define('_DECONNEXION','Disconnect');
define('_CONNEXION','Sign In');
define('_PLANNING','Planning');
define('_REGISTRATION','Register');
define('_PROVIDERS','Our Providers');
define('_WE','About Us');

//Connexion/Registration
define('_LASTNAME','Last Name');
define('_FIRSTNAME','First Name');
define('_EMAIL','Address Email');
define('_PHONENUMBER','Phone number');
define('_ADDRESS','Address');
define('_CITY','City');
define('_PASSWORD','Password');
define('_PASSWORD2','Confirm Password');
define('_CLIENT','Client');
define('_PROVIDER','Provider');
define('_AGENCY','Choice of agency');

//Error verif_connexion.php
define('E_CONNEXION1','You must fill in all the fields');
define('E_CONNEXION2','Incorrect email');
define('E_CONNEXION3','Incorrect password');

//Error verif_inscription.php
define('E_REGISTRATION1','You must fill in all the fields');
define('E_REGISTRATION2','Your last name, first name and city name must be between 2 and 50 characters long');
define('E_REGISTRATION3','Your last name, first name and city name must contain only letters, spaces or _');
define('E_REGISTRATION4','Your phone number is incorrect');
define('E_REGISTRATION5','Your address must be between 9 and 140 characters long');
define('E_REGISTRATION6','Your address must have the following syntax: "{street number} street {street name}"');
define('E_REGISTRATION7','Your address number is incorrect');
define('E_REGISTRATION8','Your street name must be at least 3 characters long');
define('E_REGISTRATION9','Your street name should only have letters');
define('E_REGISTRATION10','Your Password must contain at least 2 uppercase letters, 2 numbers and 4 lowercase letters');
define('E_REGISTRATION11','Your passwords do not match');
define('E_REGISTRATION12','Please select an agency');
define('E_REGISTRATION13','Your email must be a maximum of 140 characters long');
define('E_REGISTRATION14','Your email is not valid');
define('E_REGISTRATION15','This email is already in use');

define('OK_REGISTRATION','Your account has been created');

//Service
define('_SERVICE','Service');
define('_SERVICEDEMAND','Service Demand ');
define('E_SERVICE','No services available');

define('_SINGLETARIFF','Single tariff');
define('_RECURRINGTARIFF','Recurring tariff');
define('_MINIMUMOF','minimum of');
define('_ORDERED','ordered');
define('_INCLTAXES','incl taxes');
define('_NBTAKE','number taken');
define('_ADDTOCART','Add to cart');
define('_AVAILABILITY','Day/Time available');
define('_TO1','to');
define('_TO2','to');
define('_H','h');

//Cart
define('_CART','Cart');
define('_CARTEMPTY','Your cart is empty');
define('_NUMBER','Number');
define('_HOUR','Hour');
define('_HOURS','Hours');
define('_DAY','Day');
define('_DAYS','Days');
define('_CANCEL','Cancel');

//Estimate/Bill
define('_ESTIMATE','Estimate');
define('_STREETLUXERYSERVICE','242 Faubourg Saint-Antoine Street');
define('_ADDRESSEE','Addressee');
define('_REGISTRATIONDATE','Registration date');
define('_QUANTITY','Quantity');
define('_UNITPRICE','Unit price');
define('_TOTAL','Total');
define('_VALIDESTIMATE','This estimate is valid until');
define('_TOTALESTIMATE','Total estimate');
define('_BILL','Bill');
define('_BUY','Buy');
define('_TOTALBILL','Total bill');
define('_DETAILS','Details');
define('_LESS','Less');
define('_ESTIMATEEMPTY','You don\'t have a estimate');

//Intervention
define('_INTERVENTIONS','Our interventions');
define('_INTERVENTION','Intervention');
define('_INTERVENTIONDEMAND','Intervention Demand');
define('_HISTORY','History');
define('E_PLANNING','You don\'t have an intervention in progress');
define('E_HISTORY','you don\'t have an intervention performed');
define('_CREATE','Create');
define('_NOPROVIDER','No providers have been found to carry out your intervention');
define('_INTERVENTIONCREATE','Your intervention has been taken into account');
define('_USESUBSCRIPTION','Using a subscription');
define('_REST','rest');
define('_YOUMISS','You miss');
define('_CREDITS','credits');
define('_NEGATIVENUMBER','Negative numbers are not allowed');

//Subscription
define('_SUBSCRIPTION','Subscription');
define('_YOURSUBSCRIPTION','Your subscriptions');
define('_YEAR','year');
define('_SERVICEMONTH','of services/month');
define('_UNLIMITEDINQUIRIES','Unlimited inquiries');
define('_BENEFITPRIVILEGED','Benefit from unlimited privileged access');
define('_OF','of');
define('E_SUBSCRIPTION','No subscriptions available');
define('_NOSUBSCRIPTION','You have no current subscriptions');
define('_ENDSUBSCRIPTION','Your subscription ended on');
define('_STILLHAVE','You still have');
define('_ONSUBSCRIPTION','on your subscription');
define('_SUBSCRIPTIONBUY','Your subscription has been taken into account');
define('_ALREADYSUBSCRIPTION','You already have this subscription');
define('_REFUND','REFUND ME');
define('_REFUNDOK','VALID');
define('_REFUNDKO','REFUSE');


//Day of the Week
define('_MONDAY','Monday');
define('_TUESDAY','Tuesday');
define('_WEDNESDAY','Wednesday');
define('_THURSDAY','Thursday');
define('_FRIDAY','Friday');
define('_SATURDAY','Saturday');
define('_SUNDAY','Sunday');

?>
