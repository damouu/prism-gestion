define({ "api": [
  {
    "type": "delete",
    "url": "/exemplaires/:id",
    "title": "delete",
    "group": "Exemplaires",
    "name": "delete",
    "version": "0.1.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Identifiant primaire de l'exemplaire</p>"
          }
        ]
      }
    },
    "description": "<p>Supprime un exemplaire</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Message réponse</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"type\": \"success\",\n     \"code\": 200,\n     \"message\": \"L'exemplaire :id a bien été supprimé.\"\n\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "500": [
          {
            "group": "500",
            "optional": false,
            "field": "InternalError",
            "description": "<p>Internal Server Error..</p>"
          }
        ],
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Ressource non trouvée.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Internal Error:",
          "content": "HTTP/1.1 500 Internal Error\n{\n     \"type\": \"error\",\n     \"code\": 500,\n     \"message\": \"Internal Server Error.\"\n}",
          "type": "json"
        },
        {
          "title": "Not Found:",
          "content": "HTTP/1.1 404 Not Found\n{\n     \"type\": \"error\",\n     \"code\": 404,\n     \"message\": \"Ressource non trouvée.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Controllers/ExemplaireController.php",
    "groupTitle": "Exemplaires"
  },
  {
    "type": "get",
    "url": "/exemplaires",
    "title": "getAll",
    "group": "Exemplaires",
    "name": "getAll",
    "version": "0.1.0",
    "description": "<p>Récupère tous les exemplaires selon le paramètre passé dans le ?select. Paramètres possibles: ?select=reforme ?select=service</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "exemplaires",
            "description": "<p>Liste d'exemplaires</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.id",
            "description": "<p>Identifiant primaire de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.materiel",
            "description": "<p>Identifiant étranger du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.reference",
            "description": "<p>Référence UL de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.fournisseur",
            "description": "<p>Identifiant étranger du fournisseur</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.prix_ht",
            "description": "<p>Prix Hors Taxes d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.prix_ttc",
            "description": "<p>Prix Toutes Taxes Comprises d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.num_serie",
            "description": "<p>Numéro de série constructeur de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.url",
            "description": "<p>URL de référence de l'objet</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.stockage",
            "description": "<p>Lieu de stockage de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaire.etat",
            "description": "<p>Etat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.num_ex",
            "description": "<p>Numéro d'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.financement",
            "description": "<p>Organisme de financement d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.bon_commande",
            "description": "<p>Numéro de bon de commande</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.immobilisation",
            "description": "<p>Numéro de fiche d'immobilisation</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_achat",
            "description": "<p>Date d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_modif",
            "description": "<p>Date de dernière modification</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_Sortie",
            "description": "<p>Date de sortie de l'exemplaire</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"type\": \"success\",\n     \"code\": 200,\n     \"exemplaires\": [\n                         {\n                             \"id\": 2,\n                             \"materiel\": 1,\n                             \"reference\": \"ESOSHAJSD\",\n                             \"fournisseur\": 2,\n                             \"prix_ht\": \"200.00\",\n                             \"prix_ttc\": \"200.00\",\n                             \"num_serie\": \"AZEYI738-3739\",\n                             \"url\": \"http://www.google.fr\",\n                             \"stockage\": \"701\",\n                             \"etat\": \"réparation\",\n                             \"num_ex\": 2,\n                             \"financement\": \"Tech de co\",\n                             \"bon_commande\": \"345678AZ\",\n                             \"immobilisation\": \"4567890987\",\n                             \"date_achat\": \"2019-04-18\",\n                             \"date_modif\": null,\n                             \"date_sortie\": null\n                         },\n                         {\n                             \"id\": 3,\n                             \"materiel\": 1,\n                             \"reference\": \"ESOSHDDD\",\n                             \"fournisseur\": 1,\n                             \"prix_ht\": \"200.00\",\n                             \"prix_ttc\": \"200.00\",\n                             \"num_serie\": \"AZEYTUE-YSJ\",\n                             \"url\": \"http://www.google.fr\",\n                             \"stockage\": \"bureau\",\n                             \"etat\": \"emprunté\",\n                             \"num_ex\": 3,\n                             \"financement\": \"UL\",\n                             \"bon_commande\": \"TYUI567890\",\n                             \"immobilisation\": \"45654567890\",\n                             \"date_achat\": \"2019-04-18\",\n                             \"date_modif\": null,\n                             \"date_sortie\": null\n                         }\n                    ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>La méthode n'est pas correctement utilisée.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Ressource non trouvée.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"La méthode n'est pas correctement utilisée.\"\n}",
          "type": "json"
        },
        {
          "title": "Not Found:",
          "content": "HTTP/1.1 404 Not Found\n{\n     \"type\": \"error\",\n     \"code\": 404,\n     \"message\": \"Ressource non trouvée.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Controllers/ExemplaireController.php",
    "groupTitle": "Exemplaires"
  },
  {
    "type": "get",
    "url": "/exemplaires/:id/materiels",
    "title": "getExemplaireMateriel",
    "group": "Exemplaires",
    "name": "getExemplaireMateriel",
    "version": "0.1.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Identifiant primaire de l'exemplaire</p>"
          }
        ]
      }
    },
    "description": "<p>Récupère un exemplaire avec les informations de materiel</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "exemplaires",
            "description": "<p>Liste d'exemplaires</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.id",
            "description": "<p>Identifiant primaire de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "exemplaires.materiel",
            "description": "<p>Objet étranger du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.materiel.id",
            "description": "<p>Identifiant primaire du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.materiel.constructeur",
            "description": "<p>Constructeur du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.materiel.modele",
            "description": "<p>Modèle du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.materiel.description",
            "description": "<p>Description du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.materiel.nb_ex",
            "description": "<p>Nombre d'exemplaires du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.materiel.type",
            "description": "<p>Identifiant étranger du type</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.materiel.date_creation",
            "description": "<p>Date de création du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.materiel.date_modification",
            "description": "<p>Date de dernière modification du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.materiel.date_suppression",
            "description": "<p>Date de suppression du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.reference",
            "description": "<p>Référence UL de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.fournisseur",
            "description": "<p>Identifiant étranger du fournisseur</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.prix_ht",
            "description": "<p>Prix Hors Taxes d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.prix_ttc",
            "description": "<p>Prix Toutes Taxes Comprises d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.num_serie",
            "description": "<p>Numéro de série constructeur de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.url",
            "description": "<p>URL de référence de l'objet</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.stockage",
            "description": "<p>Lieu de stockage de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaire.etat",
            "description": "<p>Etat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.num_ex",
            "description": "<p>Numéro d'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.financement",
            "description": "<p>Organisme de financement d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.bon_commande",
            "description": "<p>Numéro de bon de commande</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.immobilisation",
            "description": "<p>Numéro de fiche d'immobilisation</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_achat",
            "description": "<p>Date d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_modif",
            "description": "<p>Date de dernière modification</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_Sortie",
            "description": "<p>Date de sortie de l'exemplaire</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"type\": \"success\",\n     \"code\": 200,\n     \"exemplaire\": {\n                       \"id\": 2,\n                       \"materiel\": {\n                                     \"id\": 1,\n                                     \"constructeur\": \"Canon\",\n                                     \"modele\": \"EOS X5\",\n                                     \"description\": \"appareil photo classique\",\n                                     \"nb_ex\": 2,\n                                     \"type\": 1,\n                                     \"date_creation\": \"2019-04-16\",\n                                     \"date_modification\": \"2019-05-10\",\n                                     \"date_suppression\": null\n                                     },\n                       \"reference\": \"ESOSHAJSD\",\n                       \"fournisseur\": 1,\n                       \"prix_ht\": \"200.00\",\n                       \"prix_ttc\": \"200.00\",\n                       \"num_serie\": \"AZEYI738-3739\",\n                       \"url\": \"http://www.google.fr\",\n                       \"stockage\": \"701\",\n                       \"etat\": \"réparation\",\n                       \"num_ex\": 2,\n                       \"financement\": \"Tech de co\",\n                       \"bon_commande\": \"345678AZ\",\n                       \"immobilisation\": \"4567890987\",\n                       \"date_achat\": \"2019-04-18\",\n                       \"date_modif\": null,\n                       \"date_sortie\": null\n                    }\n\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>La méthode n'est pas correctement utilisée.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Ressource non trouvée.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"La méthode n'est pas correctement utilisée.\"\n}",
          "type": "json"
        },
        {
          "title": "Not Found:",
          "content": "HTTP/1.1 404 Not Found\n{\n     \"type\": \"error\",\n     \"code\": 404,\n     \"message\": \"Ressource non trouvée.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Controllers/ExemplaireController.php",
    "groupTitle": "Exemplaires"
  },
  {
    "type": "get",
    "url": "/exemplaires/:id",
    "title": "getOne",
    "group": "Exemplaires",
    "name": "getOne",
    "version": "0.1.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Identifiant primaire de l'exemplaire</p>"
          }
        ]
      }
    },
    "description": "<p>Récupère un exemplaire selon le paramètre passé dans le ?select. Paramètres possibles: ?select=reforme ?select=service</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "exemplaires",
            "description": "<p>Liste d'exemplaires</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.id",
            "description": "<p>Identifiant primaire de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "exemplaires.materiel",
            "description": "<p>Objet étranger du matériel</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.reference",
            "description": "<p>Référence UL de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "exemplaires.fournisseur",
            "description": "<p>Objet étranger du fournisseur</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.prix_ht",
            "description": "<p>Prix Hors Taxes d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.prix_ttc",
            "description": "<p>Prix Toutes Taxes Comprises d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.num_serie",
            "description": "<p>Numéro de série constructeur de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.url",
            "description": "<p>URL de référence de l'objet</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.stockage",
            "description": "<p>Lieu de stockage de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaire.etat",
            "description": "<p>Etat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "exemplaires.num_ex",
            "description": "<p>Numéro d'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.financement",
            "description": "<p>Organisme de financement d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.bon_commande",
            "description": "<p>Numéro de bon de commande</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "exemplaires.immobilisation",
            "description": "<p>Numéro de fiche d'immobilisation</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_achat",
            "description": "<p>Date d'achat de l'exemplaire</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_modif",
            "description": "<p>Date de dernière modification</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "exemplaires.date_Sortie",
            "description": "<p>Date de sortie de l'exemplaire</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"type\": \"success\",\n     \"code\": 200,\n     \"exemplaire\": {\n                       \"id\": 2,\n                       \"materiel\": {...},\n                       \"reference\": \"ESOSHAJSD\",\n                       \"fournisseur\": {...},\n                       \"prix_ht\": \"200.00\",\n                       \"prix_ttc\": \"200.00\",\n                       \"num_serie\": \"AZEYI738-3739\",\n                       \"url\": \"http://www.google.fr\",\n                       \"stockage\": \"701\",\n                       \"etat\": \"réparation\",\n                       \"num_ex\": 2,\n                       \"financement\": \"Tech de co\",\n                       \"bon_commande\": \"345678AZ\",\n                       \"immobilisation\": \"4567890987\",\n                       \"date_achat\": \"2019-04-18\",\n                       \"date_modif\": null,\n                       \"date_sortie\": null\n                    }\n\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>La méthode n'est pas correctement utilisée.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Ressource non trouvée.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"La méthode n'est pas correctement utilisée.\"\n}",
          "type": "json"
        },
        {
          "title": "Not Found:",
          "content": "HTTP/1.1 404 Not Found\n{\n     \"type\": \"error\",\n     \"code\": 404,\n     \"message\": \"Ressource non trouvée.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Controllers/ExemplaireController.php",
    "groupTitle": "Exemplaires"
  },
  {
    "type": "patch",
    "url": "/exemplaires/:id",
    "title": "patch",
    "group": "Exemplaires",
    "name": "patch",
    "version": "0.1.0",
    "description": "<p>Remet en service un exemplaire supprimé grace au parametre ?select=reforme</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Message",
            "description": "<p>de réussite</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"type\": \"success\",\n     \"code\": 200,\n     \"exemplaire\": \"reussi\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>La méthode n'est pas correctement utilisée.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Ressource non trouvée.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"La méthode n'est pas correctement utilisée.\"\n}",
          "type": "json"
        },
        {
          "title": "Not Found:",
          "content": "HTTP/1.1 404 Not Found\n{\n     \"type\": \"error\",\n     \"code\": 404,\n     \"message\": \"Ressource non trouvée.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Controllers/ExemplaireController.php",
    "groupTitle": "Exemplaires"
  },
  {
    "type": "post",
    "url": "/exemplaires",
    "title": "post",
    "group": "Exemplaires",
    "name": "post",
    "version": "0.1.0",
    "description": "<p>Crée un nouvel exemplaire avec les informations fournies</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "materiel",
            "description": "<p>Identifiant primaire d'un matériel</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "reference",
            "description": "<p>Référence UL de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "etat",
            "description": "<p>Etat de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "fournisseur",
            "description": "<p>Identifiant primaire d'un fournisseur</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "prix_ttc",
            "description": "<p>Prix Toutes Taxes Comprises de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "prix_ht",
            "description": "<p>Prix Hors Taxes de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "num_serie",
            "description": "<p>Numéro de série constructeur</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "financement",
            "description": "<p>Financement de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "bon_commande",
            "description": "<p>Numéro de bon de commande de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "date_achat",
            "description": "<p>Date d'achat de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "stockage",
            "description": "<p>Lieu de stockage de l'exemplaire</p>"
          }
        ],
        "optional": [
          {
            "group": "optional",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>Url de référence de l'exemplaire</p>"
          },
          {
            "group": "optional",
            "type": "String",
            "optional": false,
            "field": "immobilisation",
            "description": "<p>Numéro de fiche d'immobilisation</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Message",
            "description": "<p>de réussite</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"type\": \"success\",\n     \"code\": 200,\n     \"exemplaire\": \"reussi\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "500": [
          {
            "group": "500",
            "optional": false,
            "field": "InternalError",
            "description": "<p>Internal Server Error..</p>"
          }
        ],
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>La méthode n'est pas correctement utilisée.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "ValidationError",
            "description": "<p>Une ou plusieurs validations n'ont pas été respectées.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Ressource non trouvée.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"La méthode n'est pas correctement utilisée.\"\n}",
          "type": "json"
        },
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"Une ou plusieurs validations n'ont pas été respectées.\"\n     \"validation\": \"erreurs à afficher\"\n}",
          "type": "json"
        },
        {
          "title": "Not Found:",
          "content": "HTTP/1.1 404 Not Found\n{\n     \"type\": \"error\",\n     \"code\": 404,\n     \"message\": \"Ressource non trouvée.\"\n}",
          "type": "json"
        },
        {
          "title": "Internal Error:",
          "content": "HTTP/1.1 500 Internal Error\n{\n     \"type\": \"error\",\n     \"code\": 500,\n     \"message\": \"Internal Server Error.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Controllers/ExemplaireController.php",
    "groupTitle": "Exemplaires"
  },
  {
    "type": "put",
    "url": "/exemplaires/:id",
    "title": "put",
    "group": "Exemplaires",
    "name": "put",
    "version": "0.1.0",
    "description": "<p>Modifie ou crée un exemplaire avec les informations fournies</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "materiel",
            "description": "<p>Identifiant primaire d'un matériel</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "reference",
            "description": "<p>Référence UL de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "etat",
            "description": "<p>Etat de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "fournisseur",
            "description": "<p>Identifiant primaire d'un fournisseur</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "prix_ttc",
            "description": "<p>Prix Toutes Taxes Comprises de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "prix_ht",
            "description": "<p>Prix Hors Taxes de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "num_serie",
            "description": "<p>Numéro de série constructeur</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "financement",
            "description": "<p>Financement de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "bon_commande",
            "description": "<p>Numéro de bon de commande de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "date_achat",
            "description": "<p>Date d'achat de l'exemplaire</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "stockage",
            "description": "<p>Lieu de stockage de l'exemplaire</p>"
          }
        ],
        "optional": [
          {
            "group": "optional",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>Url de référence de l'exemplaire</p>"
          },
          {
            "group": "optional",
            "type": "String",
            "optional": false,
            "field": "immobilisation",
            "description": "<p>Numéro de fiche d'immobilisation</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "201": [
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "Message",
            "description": "<p>de réussite</p>"
          }
        ],
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Type de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Code de la réponse</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Message",
            "description": "<p>de réussite</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SuccessAdd-Response:",
          "content": "HTTP/1.1 201 CREATED\n{\n     \"type\": \"success\",\n     \"code\": 201,\n     \"exemplaire\": \"reussi\"\n}",
          "type": "json"
        },
        {
          "title": "SuccessEdit-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"type\": \"success\",\n     \"code\": 200,\n     \"exemplaire\": \"reussi\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "500": [
          {
            "group": "500",
            "optional": false,
            "field": "InternalError",
            "description": "<p>Internal Server Error..</p>"
          }
        ],
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>La méthode n'est pas correctement utilisée.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "ValidationError",
            "description": "<p>Une ou plusieurs validations n'ont pas été respectées.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Ressource non trouvée.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"La méthode n'est pas correctement utilisée.\"\n}",
          "type": "json"
        },
        {
          "title": "Bad Request:",
          "content": "HTTP/1.1 405 Bad Request\n{\n     \"type\": \"error\",\n     \"code\": 405,\n     \"message\": \"Une ou plusieurs validations n'ont pas été respectées.\"\n     \"validation\": \"erreurs à afficher\"\n}",
          "type": "json"
        },
        {
          "title": "Not Found:",
          "content": "HTTP/1.1 404 Not Found\n{\n     \"type\": \"error\",\n     \"code\": 404,\n     \"message\": \"Ressource non trouvée.\"\n}",
          "type": "json"
        },
        {
          "title": "Internal Error:",
          "content": "HTTP/1.1 500 Internal Error\n{\n     \"type\": \"error\",\n     \"code\": 500,\n     \"message\": \"Internal Server Error.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Controllers/ExemplaireController.php",
    "groupTitle": "Exemplaires"
  }
] });
