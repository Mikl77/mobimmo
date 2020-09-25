$(document).ready(function () {

   //Cache la partie formulaire pour l ajout d un utilisateur le temps de la recherche du mail
   $('#add_client_form').hide();
   $('#mail_search_part').show();

   //Gestion de la page mailBox
   $(function () {
      //Enable check and uncheck all functionality
      $('.checkbox-toggle').click(function () {
         var clicks = $(this).data('clicks')
         if (clicks) {
            //Uncheck all checkboxes
            $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
            $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
         } else {
            //Check all checkboxes
            $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
            $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
         }
         $(this).data('clicks', !clicks)
      })

      //Handle starring for glyphicon and font awesome
      $('.mailbox-star').click(function (e) {
         e.preventDefault()
         //detect type
         var $this = $(this).find('a > i')
         var glyph = $this.hasClass('glyphicon')
         var fa = $this.hasClass('fa')

         //Switch states
         if (glyph) {
            $this.toggleClass('glyphicon-star')
            $this.toggleClass('glyphicon-star-empty')
         }

         if (fa) {
            $this.toggleClass('fa-star')
            $this.toggleClass('fa-star-o')
         }
      })
   })

   //Gestion du textarea sur la page compose mail
   $(function () {
      //Add text editor
      $('#compose-textarea').summernote()
   })

   var csrf_name = document.getElementById('csrf_name').value;
   var csrf_value = document.getElementById('csrf_value').value;
   // todo a retravailler cela car ne fonctionne pas bien
   if (document.getElementById('estate_id')) {
      var estate_id = document.getElementById('estate_id').value;
   }

   //gestion formulaire
   bsCustomFileInput.init();

   // gestion du menu et de la rubrique en class active
   var active_a = $('.active');
   active_a.each(function () {
      if (active_a.hasClass('nav-link')) {
         var active_li = active_a.parent('li');
         var active_ul = active_li.parent('ul');
         var parent_li = active_ul.parent('li');
         parent_li.addClass('menu-open');
         parent_li.children('.nav-link')
         parent_li.children('.nav-link').addClass('active');
      }
   })

   // initialize with defaults
   $("#input-fr").fileinput({
      'language': "fr",
      'theme': 'fas',
      'uploadAsync': true,
      'uploadUrl': '/action',
      'uploadExtraData': {
         'csrf_name': csrf_name,
         'csrf_value': csrf_value,
         'action': 'file-input',
         'estate_id': estate_id
      },
      'showUpload': true,
      'allowedFileExtensions': ['jpg', 'bmp'],
      'overwriteInitial': true,
      'minFileCount': 1,
      'maxFileCount': 5
   }).on('fileuploaded', function (event, data, id, index) {
      loadImages();
   });

   //gestion de l'ajout de l'image principale
   $("#main_picture").fileinput({
      'language': "fr",
      'theme': 'fas',
      'showUpload': false,
      'previewFileType': 'any'
   });

   //gestion du changement de l'image principale page SHOW_ESTATE
   $("#input-main_picture").fileinput({
      'language': "fr",
      'theme': 'fas',
      'uploadAsync': true,
      'uploadUrl': '/action',
      'uploadExtraData': {
         'csrf_name': csrf_name,
         'csrf_value': csrf_value,
         'action': 'main-file-update',
         'estate_id': estate_id
      },
      'allowedFileExtensions': ['jpg', 'bmp'],
      'showUpload': true,
      'previewFileType': 'any'
   }).on('fileuploaded', function (event, data, id, index) {
      loadMainImages();
      loadImages();
   });

   //chargement de l image principale apres upload
   function loadMainImages() {
      $.ajax({
         url: '/action',
         method: 'POST',
         data: {
            action: 'load_main_image',
            csrf_name: csrf_name,
            csrf_value: csrf_value,
            estate_id: estate_id
         },
         success: function (data) {
            $('#database_main_img').hide();
            $('#uploaded_main_img').html(data);
         }
      })
   }

   //chargement des images secondaires apres upload
   function loadImages() {
      $.ajax({
         url: '/action',
         method: 'POST',
         data: {
            action: 'load_images',
            csrf_name: csrf_name,
            csrf_value: csrf_value,
            estate_id: estate_id
         },
         success: function (data) {
            $('#database_img').hide();
            $('#uploaded_img').html(data);
         }
      })
   }

   //chargement des utilsateurs en relation
   function loadUsers() {

   }

   //chargement des fiches locataires apres ajout
   function loadLocataires() {

      $.ajax({
         url: '/action',
         method: 'POST',
         data: {
            action: 'load_locataires',
            csrf_name: csrf_name,
            csrf_value: csrf_value,
            locataire_list: document.getElementById('locataires_list').value
         },
         success: function (data) {
            $('#list').html(data);
         }
      })
   }

   //gestion de la suppression des images
   $("#image_part").on("click", "i.close", function (e) {
      e.preventDefault();
      var filename = $(this).attr('id');
      $.confirm({
         title: 'Confirmation!',
         content: 'Souhaitez-vous supprimer cette photo?',
         type: 'red',
         typeAnimated: true,
         buttons: {
            yes: {
               text: 'Oui',
               action: function () {
                  //requete ajax pour suppression de la photo
                  $.ajax({
                     url: '/action',
                     method: 'POST',
                     data: {
                        action: 'delete_image',
                        csrf_name: csrf_name,
                        csrf_value: csrf_value,
                        filename: filename
                     },
                     success: function () {
                        loadImages();
                     }
                  })
               }
            },
            no: {
               text: 'Non',
               action: function () {
               }
            },
         }
      })
   })

   //PAGE SHOW_ESTATE
   //GESTION DES SELECT
   //chargement des champs select en page

   var $estate_status = $("#estate_status_id").val();
   $('.estate_status option')
       .removeAttr('selected')
       .filter('[value="' + $estate_status + '"]')
       .attr('selected', true);

   var $estate_type = $("#estate_type_id").val();
   $('.estate_type option')
       .removeAttr('selected')
       .filter('[value="' + $estate_type + '"]')
       .attr('selected', true);

   var $rooms = $("#estate_rooms_id").val();
   $('.estate_rooms option')
       .removeAttr('selected')
       .filter('[value="' + $rooms + '"]')
       .attr('selected', true);

   var $bedrooms = $("#estate_bedrooms_id").val();
   $('.estate_bedrooms option')
       .removeAttr('selected')
       .filter('[value="' + $bedrooms + '"]')
       .attr('selected', true);

   var $bathrooms = $("#estate_bathrooms_id").val();
   $('.estate_bathrooms option')
       .removeAttr('selected')
       .filter('[value="' + $bathrooms + '"]')
       .attr('selected', true);

   var $parking = $("#estate_parking_id").val();
   $('.estate_parking option')
       .removeAttr('selected')
       .filter('[value="' + $parking + '"]')
       .attr('selected', true);

   var $build_year = $("#estate_build_year_id").val();
   $('.estate_build_year option')
       .removeAttr('selected')
       .filter('[value="' + $build_year + '"]')
       .attr('selected', true);

   //Gestion de l update des donnes du bien
   $("#update_estate_form").on("click", function (e) {
      e.preventDefault();
      var estate_title = document.getElementById('estate_title').value;
      $.ajax({
         url: '/action',
         method: 'POST',
         data: {
            'action': "update_estate_data",
            'csrf_name': csrf_name,
            'csrf_value': csrf_value,
            'estate_id': estate_id,
            'title': estate_title
         },
         success: function (data) {
            $.confirm({
               title: '- Mobimmo -',
               content: 'Données mises à jour!',
               type: 'green',
               typeAnimated: true,
               buttons: {
                  tryAgain: {
                     text: 'ok',
                     btnClass: 'btn-green',
                  },
               }
            });
         }
      })

   })

   $("#mail_search").on('click', function (e) {
      e.preventDefault();
      var mail_to_find = document.getElementById('mail_to_find').value;
      $.ajax({
         url: '/action',
         method: 'POST',
         dataType: 'json',
         data: {
            action: 'check_mail',
            csrf_name: csrf_name,
            csrf_value: csrf_value,
            mail_to_find: mail_to_find
         },
         success: function (data) {
            if (data) {
               $.confirm({
                  title: 'Email existant',
                  content: 'Nous avons trouvé, ' + data.first_name + ' ' + data.last_name,
                  type: 'green',
                  typeAnimated: true,
                  buttons: {
                     tryAgain: {
                        text: 'Sélectionner cet utilisateur',
                        btnClass: 'btn-green',
                        action: function () {
                           var first_user_id = $('#first_user_id').val();
                           var relation_type_id = $('#relation_type_id').val();
                           if (first_user_id == data.id) {
                              $.alert(
                                  'Il s\'agit de vous...'
                              )
                           }
                           //creation de la relation entre les utilisateurs
                           $.ajax({
                                  url: '/action',
                                  method: 'POST',
                                  data: {
                                     action: 'add_relation_between_users',
                                     csrf_name: csrf_name,
                                     csrf_value: csrf_value,
                                     first_user_id: first_user_id,
                                     second_user_id: data.id,
                                     relation_type_id: relation_type_id
                                  },
                                  success: function () {
                                     //rafraichir la liste des utilisateurs
                                     loadUsers();
                                  }
                               }
                           )
                        },
                     },
                  }
               });
            }
         },
         error: function () {
            //si une erreur se produit dans la recherche du mail.
            $('#mail_search_part').hide();
            $('#add_client_form').show();
         }
      })
   })

   // mail search dans la page ajouter nouveau contrat
   $("#mail_search_loca").on('click', function (e) {
      e.preventDefault();
      var mail_to_find = document.getElementById('mail_to_find').value;
      $.ajax({
         url: '/action',
         method: 'POST',
         dataType: 'json',
         data: {
            action: 'check_mail',
            csrf_name: csrf_name,
            csrf_value: csrf_value,
            mail_to_find: mail_to_find
         },
         success: function (data) {
            if (data) {
               $.confirm({
                  title: 'Email existant',
                  content: 'Nous avons trouvé, ' + data.first_name + ' ' + data.last_name,
                  type: 'green',
                  typeAnimated: true,
                  buttons: {
                     tryAgain: {
                        text: 'Sélectionner cet utilisateur',
                        btnClass: 'btn-green',
                        action: function () {
                           var first_user_id = data.id;
                           var locataires_list = document.getElementById('locataires_list').value;
                           //Ajout du locataire
                           $.ajax({
                                  url: '/action',
                                  method: 'POST',
                                  data: {
                                     action: 'add_locataire',
                                     csrf_name: csrf_name,
                                     csrf_value: csrf_value,
                                     first_user_id: first_user_id,
                                     locataires_list: locataires_list
                                  },
                                  success: function (data1) {
                                     //rafraichir la liste des utilisateurs
                                     $('#locataires_list').val(data1);
                                     loadLocataires();
                                     $('#modal-add-client').modal('hide');
                                  }
                               }
                           )
                        },
                     },
                  }
               });
            }
         },
         error: function () {
            //si une erreur se produit dans la recherche du mail.
            $('#mail_search_part').hide();
            $('#add_client_form').show();
         }
      })
   })


   //Ajouter un nouveau contrat

   $("#add-new-contract").on('click', function (e) {
      e.preventDefault();

      //todo Validation du bon remplissage des champs

      //todo recuperation des valeurs inserees
      //contrat_type ... vente vs location
      //contract_type2 meuble ou non si location
      //estate_id
      //loca_list

      $.ajax({
         url: '/action',
         method: 'POST',
         data: {
            action: 'add_new_contract',
            csrf_name: csrf_name,
            csrf_value: csrf_value,
            contract_type: 1,
            contract_type_2: 0,
            estate_id: 6,
            loca_list: ['104', '6', '83']
         },
         success: function (data) {
         }
      })
   })


//derniere ligne
})
