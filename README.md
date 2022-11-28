# Webbprogramming-Example
This repo contains examples and the API used in the Webbprograming course.

- /Examples - Here you find code examples used in the course
- /SPA-templates - Here you find single-page-application (SPA) templates 
- /admin - Here you find tools to manipulate booking data
- /booking - Here you find the booking API that your webb application should use

# Webbprogrammering-API
All data is returned as XML.

## Sample usage Fetch API and JSON parameters
Call the appropropriate service with the required (and optional) parameters. For example, to create a new customer the following Fetch-call can be used:
~~~ js
try {
    const url = "../booking/makecustomer_XML.php";

    // Manually create the JSON object to be submitted to our API
    const params = {};
    const inputs = document.querySelectorAll("#make-customer-form input[type='text']");
    for(const input of inputs){
        console.log(input)
        params[input.name]=input.value;
    }

    // Make request
    const response = await fetch(url,{
        method: "post",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(params)
    });

    // Act on the response
    if(!response.ok){
        throw new Error(await response.text());
    }
    const response_text = await response.text(); // <-- Contains the XML returned byt the API
}catch(err){
    console.log("error", err);
}
~~~
## Sample usage Fetch API and FormData paramters
Call the appropropriate service with the required (and optional) parameters. For example, to create a new customer the following Fetch-call can be used:
~~~ js
try {
    const url = "../booking/makecustomer_XML.php";

    // Manually create the form data to be submitted to our API
    const form = new FormData();
    const inputs = document.querySelectorAll("#make-customer-form input[type='text']");
    for(const input of inputs){
        console.log(input)
        form.append(input.name,input.value);
    }

    // Make request
    const response = await fetch(url,{
        method: "post",
        body: form
    });

    // Act on the response
    if(!response.ok){
        throw new Error(await response.text());
    }
    const response_text = await response.text(); // <-- Contains the XML returned byt the API
}catch(err){
    console.log("error", err);
}
~~~

## Sample usage Parsing XML response
When we have received a response from the API we can parse and use the data to create our dynamic web page. Here is an example of how you can parse the XML:
~~~ js
...
const response_text = await response.text();

// Parse text response as XML document
xml_parser = new DOMParser();
xml_document = xml_parser.parseFromString(response_text, "text/xml");

// Search in your XML document for data that you need (in this example we retreive all 'resource' elements)
const xml_nodes = xml_document.querySelectorAll('resource');
for (const xml_node of xml_nodes) {
    console.log(xml_node);
    for (const attribute of xml_node.attributes) {
        console.log(attribute.name, attribute.value);
    }
}
...
~~~

## Sample usage jQuery (obsolete)
Call the appropropriate service with the required (and optional) parameters. For example, to create a new customer the following AJAX-call using jQuery can be used:
~~~ js
$.ajax({
  type: 'POST',
  url: '../booking/makecustomer_XML.php',
  data: { ID: escape(customerID),
          firstname: escape(firstname),
          lastname: escape(lastname),
          email: escape(email),
          address: escape(address),
          auxdata: escape(auxdata),
        },
  success:  ResultCustomern,
  error: errormsg
  });
}
~~~

# Webbprogrammering-API Reference Manual

## booking/makecustomer_XML.php
### Description
Creates a customer.

**This API call has a built-in artifical delay of 3-5s**
### Parameters
ID **REQUIRED** ID of the customer\
firstname **REQUIRED** Customers firstname\
lastname **REQUIRED** Customers lastname\
email **REQUIRED** Customers email\
address **REQUIRED** Customers address\
auxdata _OPTIONAL_ Auxillary information about customer\
### Return data
~~~ xml
<created status="OK"/>
~~~
## booking/getcustomer_XML.php
### Description
Reads all information about a customer and updates last visit date to current date and time. Is used for logging in to a customer account.
### Parameters
customerID **REQUIRED** ID of the customer\
### Return data
~~~ xml
<customers>
  <customer id="test" firstname="test" lastname="test" address="test" lastvisit="2012-10-16 12:31:51" email="test" auxdata="None!" />
</customers>
~~~
## booking/makebooking_XML.php
### Description
Makes a booking and stores it in the database. (Deletes all temporary bookings for the user with that customerID that does not have state=2)

**This API call has a built-in artifical delay of 3-5s**
### Parameters
type **REQUIRED** the application to book resource in\
resourceID **REQUIRED** ID of the resource\
date **REQUIRED** Date of the booking. Format: 2012-10-02 (year-month-day)\
dateto **REQUIRED** End Date of the booking if there is one. Format: 2012-10-02 (year-month-day)\
customerID **REQUIRED** ID of the customer\
status **REQUIRED** Temporary or "real" booking. (1 = temporary, 2 = permanent)\
position **REQUIRED** Position of the booking (Integer)\
rebate _OPTIONAL_ Rebate, defaults to 0\
auxdata _OPTIONAL_ Auxillary data\
### Return data
~~~ xml
<result size='20' bookingcost='100' remaining='4' />		
~~~
## booking/getbookings_XML.php
### Description
Reads bookings for a resource given the resource ID. If the searchresource parameter is non-empty, uses LIKE to match the resource ID.
### Parameters
type **REQUIRED** Unique Application type. In this case Hotel_Demo for example login name of student\
resourceID _OPTIONAL_ ID of the resource\
searchresource _OPTIONAL_ If not empty searches for resource ID using like\
date _OPTIONAL_ Date of the booking. Format: 2012-10-02 (year-month-day)\
### Return data
~~~ xml
<bookings>
  <booking 
      application='Hotell_Demo'
      customerID='Leiflert'
      resourceID='1008'
      name='Karl Hotel and Resort'
      company='Karls'
      location='Exmouth'
      date='2001-07-20 00:00:00'
      dateto='2001-07-21 00:00:00'
      position='26'
      status='2'
      cost='40'
      size='24'
      auxdata=''
  />
  ...
</bookings>
~~~
## booking/getcustomerbookings_XML
### Description
Get all bookings made by a certain customer
### Parameters
type **REQUIRED** Unique Application type. In this case Hotel_Demo for example login name of student\
customerID **REQUIRED** ID of the customer\
### Return data
~~~ xml
<bookings>
  <booking 
      application='Hotell_Demo'
      customerID='Haakon'
      resourceID='1008'
      name='Karl Hotel and Resort'
      company='Karls'
      location='Exmouth'
      date='2001-07-20 00:00:00'
      dateto='2001-07-20 00:00:00'
      position='13'
      cost='280'
      category='Hotel'
      size='200'
      auxdata='None'
  />
  ...
</bookings>
~~~
## booking/getresources_XML.php
### Description
The booking/getresources_XML.php performs a search using a set of search terms. If none is given, that search term is ignored. The terms name, location and company use a logical or if more than one term is given. The full text search works in isolation from the other terms.
### Parameters
type **REQUIRED** Application type, in the example Hotel_Demo\
name _OPTIONAL_ Name of the resource\
company _OPTIONAL_ Company that used the resource\
location _OPTIONAL_ Location of the resource\
fulltext _OPTIONAL_ Tries to find a match from name, company or location\

### Return data
~~~ xml
<resources>
  <resource 
      id='1001'
      name='Pilkington Inn'
      company='Sunside Hotels'
      location='Manchester'
      size='15'
      cost='350'
      category='Hostel'
      auxdata='None'
  />
</resource>
~~~
## booking/getavailability_search_XML.php
### Description
Shows the availability information for all available dates for a given resourceID. There are 3 different ways to select resourse.
### Parameters
*Alt 1 - Search by resource id*\
type **REQUIRED** Application type\
resid **REQUIRED** The resource id\
*Alt 2 - Search by name,location,and company*\
type **REQUIRED** Application type\
name **REQUIRED** The resource name\
location **REQUIRED** The resource location\
company **REQUIRED** The resource companty\
*Alt 3 - Search with one search term for name,location, and company*\
type **REQUIRED** Application type\
fulltext **REQUIRED** Full text search much like for resource search, matches any search term.
### Return data
~~~ xml
<avail>
  <availability 
      resourceID='1008'
      name='The Laszlo Plaza'
      location='Athens'
      company='Laszlo Inc'
      size='96'
      cost='110'
      category='1'
      date='2001-01-01'
      dateto='2001-01-02'
      bookingcount='0'
      bookingcost='28.99'
      bookingclass='1'
      remaining='96'
  />
  ...
</avail>
~~~
## booking/makeresource_XML.php
### Description
Makes a new resource for the cases when we need the application to create new resources e.g. peer-to-peer hotels.
### Parameters
ID **REQUIRED** ID of the reresource\
name **REQUIRED** Name of the resource\
type **REQUIRED** Application type in the example Hotel_Demo\
company **REQUIRED** Company of the resource\
location **REQUIRED** Location of the resource\
category **REQUIRED** Category of the resource\
cost **REQUIRED** Cost of the resource\
auxdata _OPTIONAL_ Auxillary information about resource\
### Return data
~~~ xml
<created status="OK"/>
~~~
## booking/deletebooking_XML.php
### Description
Deletes a booking, for the cases that a user wants to cancel a booking of a resource.
### Parameters
ID **REQUIRED** ID of the reresource\
date **REQUIRED** Date of Booking\
customerID **REQUIRED** ID of Customer\
### Return data
~~~ xml
<deleted status="OK"/>
~~~
