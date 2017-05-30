<?php
/* ----------------------- EXPORT PATH & URI -------------------------------- */     /**
        * IMPORTANT: You need to change the location of folder where 
        *            the exported chart images/PDFs will be saved on your 
        *                         server. Please specify the path to a folder with 
        *                         write permissions in the constant SAVE_PATH below. 
        */ 
      define ( "SAVE_PATH",  "http://servicos.emater.rn.gov.br/novoceres/temp/" );
      /* Place your folder path here.*//**
        *       IMPORTANT: This constant HTTP_URI stores the HTTP reference to 
        *                  the folder where exported charts will be saved. 
        *                          Please enter the HTTP representation of that folder 
        *                          in this constant e.g., http://www.yourdomain.com/images/
        */
      define ( "HTTP_URI", "http://servicos.emater.rn.gov.br/novoceres/temp" );
      /* Define your HTTP Mapping URL here. *//**
*  OVERWRITEFILE sets whether the export handler will overwrite an existing file 
*  the newly created exported file. If it is set to false the export handler will
*  not overwrite. In this case if INTELLIGENTFILENAMING is set to true the handler
*  will add a suffix to the new file name. The suffix is a randomly generated UUID.
*  Additionally, you add a timestamp or random number as additional suffix.
* 
*/
define ( "OVERWRITEFILE", false );
define ( "INTELLIGENTFILENAMING", true ); 
define ( "FILESUFFIXFORMAT", "TIMESTAMP" ) ;
//// can be TIMESTAMP or RANDOM/* Define file over-write, auto-naming and naming suffix configuration here.*/

?>