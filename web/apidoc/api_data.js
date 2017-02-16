define({ "api": [
  {
    "type": "get",
    "url": "api/v3/city/index",
    "title": "Список городов",
    "name": "actionIndex",
    "group": "City",
    "description": "<p>Возвращает список поддерживаемых городов</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "cities",
            "description": "<p>Список городов (City)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "cities.id",
            "description": "<p>ID города</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "cities.name",
            "description": "<p>Название города</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": [city1, city2]\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/CityController.php",
    "groupTitle": "City"
  },
  {
    "type": "get",
    "url": "api/v3/company/index?category=:category",
    "title": "Список компаний",
    "name": "actionIndex",
    "group": "Company",
    "description": "<p>Возвращает список компаний по категории</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "category",
            "description": "<p>категория компании (1 - автоюристы, 2 - эвакуаторы, 3 - комиссары, 4 - акции, 5 - выездные услуги)</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "Company",
            "description": "<p>Список компаний</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Company.id",
            "description": "<p>ID компании</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Company.name",
            "description": "<p>Название города</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Company.phone",
            "description": "<p>Телефон</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Company.url",
            "description": "<p>Url</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Company.logo",
            "description": "<p>Url рисунка/логотипа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Company.description",
            "description": "<p>Описание компании</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": [Company1, Company2]\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/CompanyController.php",
    "groupTitle": "Company"
  },
  {
    "name": "actionProduce",
    "group": "Offer",
    "description": "<p>Создание/обновление предложения</p>",
    "type": "post",
    "url": "api/v3/offer/produce",
    "title": "Создание/обновление предложения",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "Offer",
            "description": "<p>Предложение</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Offer.order_id",
            "description": "<p>ID заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Offer.text",
            "description": "<p>Текст предложения</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Offer",
            "description": "<p>Преложение</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Offer\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 404,\n  \"message\": \"Заявка не найдена\"\n}",
          "type": "json"
        },
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 403,\n  \"message\": \"Необходим платеж\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OfferController.php",
    "groupTitle": "Offer"
  },
  {
    "name": "actionView",
    "group": "Offer",
    "description": "<p>Просмотр предложения</p>",
    "type": "get",
    "url": "api/v3/offer/view?id=:id",
    "title": "Просмотр предложения",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID Заказа</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Offer",
            "description": "<p>Преложение</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Offer\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 404,\n  \"message\": \"Предложение не найдена\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OfferController.php",
    "groupTitle": "Offer"
  },
  {
    "type": "get",
    "url": "api/v3/order-tag/index?query=:query",
    "title": "Список тегов",
    "name": "actionIndex",
    "group": "OrderTag",
    "description": "<p>Возвращает список тегов для заказов</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "query",
            "description": "<p>Фильтрует список тегов.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String[]",
            "optional": false,
            "field": "name",
            "description": "<p>Имя тега</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": [{\"name\": \"Ходовка\"}, {\"name\": \"Электрика\"}, {\"name\": \"Двигатель\"}, {\"name\": \"Кузов\"}]\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OrderTagController.php",
    "groupTitle": "OrderTag"
  },
  {
    "name": "actionClientCreate",
    "group": "Order",
    "description": "<p>Создание заказа клиентом</p>",
    "type": "post",
    "url": "api/v3/order/client-create",
    "title": "Создание заказа",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "Order",
            "description": "<p>Заказ</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Order.description",
            "description": "<p>Описание требуемых работ к выполнению или название запчасти</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Order.category_id",
            "description": "<p>Категория (1 - ремонт, 2 - запчасти)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.car_brand",
            "description": "<p>Марка машиный</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.car_model",
            "description": "<p>Модель машины</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.car_color",
            "description": "<p>Цвет машины</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.tagNames",
            "description": "<p>Список тегов через запятую (Ходовка, Электрика, итп), если указать не существующий тег, то он создастся</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Order\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "name": "actionClientUpdate",
    "group": "Order",
    "description": "<p>Обновление заказа клиентом</p>",
    "type": "post",
    "url": "api/v3/order/client-update",
    "title": "Обновление заказа",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "Order",
            "description": "<p>Заказ</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Order.id",
            "description": "<p>ID заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Order.description",
            "description": "<p>Описание требуемых работ к выполнению или название запчасти</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Order.category_id",
            "description": "<p>Категория (1 - ремонт, 2 - запчасти)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.car_brand",
            "description": "<p>Марка машиный</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.car_model",
            "description": "<p>Модель машины</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.car_color",
            "description": "<p>Цвет машины</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Order.tagNames",
            "description": "<p>Список тегов через запятую (Ходовка, Электрика, итп), если указать не существующий тег, то он создастся</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Order\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 400,\n  \"message\": \"Отсутствует обязательный параметр: id\"\n}\n{\n  \"status\": 403,\n  \"message\": \"Заявка принадлежит не вам\"\n}\n{\n  \"status\": 404,\n  \"message\": \"Заявка не найдена\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "name": "actionClientView",
    "group": "Order",
    "description": "<p>Просмотр заказа клиентом (смотри actionView)</p>",
    "type": "get",
    "url": "api/v3/order/client-view?id=:id",
    "title": "Просмотр заказа клиентом",
    "version": "0.0.0",
    "filename": "modules/api/controllers/v3/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "name": "actionMechCall",
    "group": "Order",
    "description": "<p>Звонок мастера клиенту</p>",
    "type": "get",
    "url": "api/v3/order/mech-call?id=:id",
    "title": "Звонок мастера клиенту",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID заказа</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": [\"login\": \"+71234567890\"]\n}",
          "type": "json"
        },
        {
          "title": "Мастеру нужно заплатить:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": [\"login\": \"need_payment\"]\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "name": "actionMechIndex",
    "group": "Order",
    "description": "<p>Просмотр списка заказов мастером</p>",
    "type": "get",
    "url": "api/v3/order/mech-index?id=:id",
    "title": "Просмотр списка заказов",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID категории заказа (1 - ремонт, 2 - запчасти)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Order[]\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "name": "actionMechView",
    "group": "Order",
    "description": "<p>Просмотр заказа мастером (смотри actionView)</p>",
    "type": "get",
    "url": "api/v3/order/mech-view?id=:id",
    "title": "Просмотр заказа мастером",
    "version": "0.0.0",
    "filename": "modules/api/controllers/v3/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "name": "actionView",
    "group": "Order",
    "description": "<p>Просмотр заказа</p>",
    "type": "get",
    "url": "api/v3/order/view?id=:id",
    "title": "Просмотр заказа",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID заказа</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Order",
            "description": "<p>Объект заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "Order.id",
            "description": "<p>ID закза</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.description",
            "description": "<p>Описание требуемых работ к выполнению или название запчасти</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "Order.category_id",
            "description": "<p>Категория (1 - ремонт, 2 - запчасти)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.car_brand",
            "description": "<p>Марка машиный</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.car_model",
            "description": "<p>Модель машины</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.car_color",
            "description": "<p>Цвет машины</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.tagNames",
            "description": "<p>Список тегов через запятую (Ходовка, Электрика, итп)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "Order.offers",
            "description": "<p>Список предложений к заказу (Offer)</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "Order.offers.reviewed",
            "description": "<p>Произведена ли оценка мастера по данному предложению</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.offers.created_at",
            "description": "<p>Дата создания преложения</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.offers.text",
            "description": "<p>Текст предложения</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Order.offers.author",
            "description": "<p>Автор предложения (User)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Order.offers.author.login",
            "description": "<p>Номер телефона Мастера</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "Order.offers.author.rating",
            "description": "<p>Рейтинг мастера</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "Order.offers.author.reviews",
            "description": "<p>Отзывы</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Order.offers.author.profile",
            "description": "<p>Профиль автора предложения (Profile)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Order.executor",
            "description": "<p>Исполнитель заказа (User)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Order.author",
            "description": "<p>Автор заказа (User)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Order\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 404,\n  \"message\": \"Заявка не найдена\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "type": "get",
    "url": "api/v3/profile/view",
    "title": "Просмотр профиля",
    "name": "ActionView",
    "group": "Profile",
    "description": "<p>Просмотр своего профиля (не сохраняет данные, нет обязательных параметров).</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Profile",
            "description": "<p>Объект профиля пользователя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.name",
            "description": "<p>Имя пользователя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.avatar",
            "description": "<p>url аватара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.phone",
            "description": "<p>телефон пользователя (user.login)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.bill_account_days",
            "description": "<p>количество оставшихся дней подписки</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.company_name",
            "description": "<p>Имя СТО/Магазина</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.company_address",
            "description": "<p>Адрес СТО/Магазина</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.company_logo",
            "description": "<p>url логотипа СТО/Магазина</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.lat",
            "description": "<p>Широта</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.lng",
            "description": "<p>И долгота где находится СТО/Магазин</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.birth_date",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.car_brand",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.car_model",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.car_color",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.car_year",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.city_id",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Profile.tagNames",
            "description": "<p>Теги заказов, на которые подписан</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Profile\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/ProfileController.php",
    "groupTitle": "Profile"
  },
  {
    "name": "actionUpdate",
    "group": "Profile",
    "description": "<p>Обновление профиля.</p>",
    "type": "post",
    "url": "api/v3/profile/update",
    "title": "Обновление профиля",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "Profile",
            "description": "<p>Профиль</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Profile.name",
            "description": "<p>Имя</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Profile.birth_date",
            "description": "<p>Дата рождения</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Profile.car_brand",
            "description": "<p>Марка машиный</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Profile.car_model",
            "description": "<p>Модель машины</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Profile.car_color",
            "description": "<p>Цвет машины</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Profile.gcm_id",
            "description": "<p>Android GSM ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Profile.apns_id",
            "description": "<p>Apple APNS ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Profile.city_id",
            "description": "<p>Город</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "Profile.tagNames",
            "description": "<p>Теги заказов, на которые подписан</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Profile\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/ProfileController.php",
    "groupTitle": "Profile"
  },
  {
    "name": "actionCreate",
    "group": "Review",
    "description": "<p>Создание отзыва к СТО/Магазину</p>",
    "type": "post",
    "url": "api/v3/review/create",
    "title": "Создание отзыва",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "Review",
            "description": "<p>Отзыв</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Review.order_id",
            "description": "<p>ID заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Review.comment",
            "description": "<p>Текст отзыва</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Review.mech_id",
            "description": "<p>ID СТО/Магазина</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Review.rating",
            "description": "<p>Оценка от 1 до 10</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Review",
            "description": "<p>Отзыв</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Review\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 404,\n  \"message\": \"Заявка не найдена\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/ReviewController.php",
    "groupTitle": "Review"
  },
  {
    "name": "actionDelete",
    "group": "Review",
    "description": "<p>Удаление отзыва к СТО/Магазину</p>",
    "type": "post",
    "url": "api/v3/review/delete",
    "title": "Удаление отзыва",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "Review",
            "description": "<p>Отзыв</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Review.id",
            "description": "<p>ID отзыва</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 403,\n  \"message\": \"Вы не можете удалить не свой отзыв\"\n}",
          "type": "json"
        },
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 404,\n  \"message\": \"Заявка не найдена\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/ReviewController.php",
    "groupTitle": "Review"
  },
  {
    "name": "actionUpdate",
    "group": "Review",
    "description": "<p>Создание отзыва к СТО/Магазину</p>",
    "type": "post",
    "url": "api/v3/review/update",
    "title": "Обновление отзыва",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "Review",
            "description": "<p>Отзыв</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Review.id",
            "description": "<p>ID отзыва</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Review.comment",
            "description": "<p>Текст отзыва</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Review.rating",
            "description": "<p>Оценка от 1 до 10</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "Review",
            "description": "<p>Отзыв</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": Review\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 404,\n  \"message\": \"Заявка не найдена\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/ReviewController.php",
    "groupTitle": "Review"
  },
  {
    "type": "get",
    "url": "api/v3/stat/call?to=:to&cat=:cat",
    "title": "Запись звонков в компании",
    "name": "actionCall",
    "group": "Stat",
    "description": "<p>Запись звонков в компании</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "to",
            "description": "<p>номер телефона вызываемого абонента</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "cat",
            "description": "<p>категория компании</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/StatController.php",
    "groupTitle": "Stat"
  },
  {
    "type": "get",
    "url": "api/v3/user/check-token",
    "title": "Проверка токена",
    "name": "actionCheckToken",
    "group": "User",
    "description": "<p>Проверка токена на валидность (не сохраняет данные, нет обязательных параметров)</p>",
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/UserController.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "api/v3/user/reset-token",
    "title": "Пересоздание токена",
    "name": "actionCheckToken",
    "group": "User",
    "description": "<p>Пересоздание токена (нет обязательных параметров).</p>",
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": {\"token\": \"<токен доступа>\"}\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/UserController.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "api/v3/user/get-code?phone=:phone",
    "title": "Получить код подтверждения",
    "name": "actionGetCode",
    "group": "User",
    "description": "<p>Получить код подтверждения через СМС (не требует авторизации)</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>номер телефона на который будет отправлено СМС. Формат: ^+\\d\\d{10}$.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200\n  \"message\": \"Вам отправлено СМС с кодом подтверждения\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 400,\n  \"message\": \"Неверный формат номера телефона\"\n}\n{\n  \"status\": 500,\n  \"message\": \"Ошибка отправки СМС\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/UserController.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "api/v3/user/verify-code?phone=:phone&code=:code",
    "title": "Подтверждение номера",
    "name": "actionVerifyCode",
    "group": "User",
    "description": "<p>Подтверждение номера (не требует авторизации)</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>номер телефона на который пришел СМС. Формат: ^+7\\d{10}$.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>код подтверждения из СМС..</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": {\n         \"login\": \"<Логин (номер телефона)>\",\n         \"token\": \"<токен доступа>\"\n     }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Ошибки:",
          "content": "{\n  \"status\": 400,\n  \"message\": \"Неверный код\"\n}\n{\n  \"status\": 404,\n  \"message\": \"Пользователь не найден (возвращается в случае указания номера телефона, отличного от номера во время вызова get-code)\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/UserController.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "api/v3/user/view?phone=:phone",
    "title": "Просмотр пользователя",
    "name": "actionView",
    "group": "User",
    "description": "<p>Просмотр профиля пользователя.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>номер телефона пользователя.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "User",
            "description": "<p>Объект пользователя</p>"
          },
          {
            "group": "Success 200",
            "type": "Profile",
            "optional": false,
            "field": "User.profile",
            "description": "<p>Объект профиля пользователя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "User.profile.name",
            "description": "<p>Дата создания преложения</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "User.rating",
            "description": "<p>Рейтинг пользователя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "User.login",
            "description": "<p>Телефон(логин) пользователя</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "User.reviews",
            "description": "<p>Отзывы</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "User.reviews.id",
            "description": "<p>ID отзыва</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "User.reviews.authorName",
            "description": "<p>Имя автора отзыва</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "User.reviews.order_id",
            "description": "<p>ID заказа, на который оставлен отзыв</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "User.reviews.mech_id",
            "description": "<p>ID мастера, которму оставлен отзыв</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "User.reviews.rating",
            "description": "<p>Оценка к отзыву 1..10</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "User.reviews.comment",
            "description": "<p>Текст отзыва</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Успех:",
          "content": "{\n  \"status\": 200,\n  \"message\": \"OK\",\n  \"data\": User\n}",
          "type": "json"
        }
      ]
    },
    "version": "3.0.0",
    "filename": "modules/api/controllers/v3/UserController.php",
    "groupTitle": "User"
  }
] });
