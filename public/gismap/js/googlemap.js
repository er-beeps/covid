 function initMap() {
     var map1 = new google.maps.Map(document.getElementById('map1'), {
         center: new google.maps.LatLng(27.700769, 85.300140),
         zoom: 6
     });
     var infowindow = new google.maps.InfoWindow();
     var oms = new OverlappingMarkerSpiderfier(map1, {
         markersWontMove: true,
         markersWontHide: true,
         basicFormatEvents: true
     });
     var markers = [];
     for (var x in json) {
         var lat = json[x].lat;
         lng = json[x].lon;
         code = json[x].code;
         name_en = json[x].name_en;
         name_lc = json[x].name_lc;
         category = json[x].category;
         sub_category = json[x].sub_category;
         Status = json[x].status;
         budget_allocated = json[x].budget_allocated;
         icons = json[x].icon;
         id = json[x].id;
         locallevel = json[x].locallevel;
         district = json[x].district_name_np;
         var detail = '/admin/project/' + id + '/edit/';
         var icon = {
             url: '/gismap/icons/' + icons + '.png', // url
             scaledSize: new google.maps.Size(20, 25), // scaled size
         };

         marker = new google.maps.Marker({
             position: new google.maps.LatLng(lat, lng),
             name: name,
             map: map1,
             icon: icon,
             content: 'कोड: ' + '<font color="red">' + code + '</font>' + '<br>' +
                 'नाम: ' + '<font color="green">' + name_en + '</font>' + '<br>' +
                 'जिल्ला: ' + '<font color="green">' + district + '</font>' + '<br>' +
                 'स्थानीय तह: ' + '<font color="blue">' + locallevel + '</font>' + '<br>' +
                 'समूह: ' + '<font color="red">' + category + '</font>' + '<br>' +
                 'उपसमूह: ' + '<font color="green">' + sub_category + '</font>' + '<br>' +
                 'परियोजना लागत: ' + '<font color="blue">' + budget_allocated + '</font>' + '<br>' +
                 'स्थिति: ' + '<font color="blue">' + Status + '</font>' + '<br>' +
                 '<a href="' + detail + '" target="_blank"><i class="fa fa-eye"></i>View Details</a>' + '<br>'
         });

         if (Status == 1) {
             marker.setIcon('/gismap/images/marker-icon.png');
         }
         google.maps.event.addListener(marker, 'spider_click', function(e) {
             infowindow.setContent(this.content);
             infowindow.open(map1, this);
         }.bind(marker));

         oms.addMarker(marker);
         markers.push(marker);
     }
     var markerCluster = new MarkerClusterer(map1, markers, {
             imagePath: '/gismap/images/m',
             minimumClusterSize: 6
         }

     );


 }