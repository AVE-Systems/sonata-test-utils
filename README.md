
# SonataTestUtils

Библиотека, предоставляющая трейты для тестирования SonataAdminBundle. В трейте
предоставлены:

    - SonataAdminFormTraitTest для тестирования форм создания и редактирования
    сущностей.
    - SonataAdminMenuTraitTest для тестирования вертикального меню.
    - SonataAdminFlashMessagesTraitTest для тестирования всплывающих сообщений.
    - SonataAdminActionsTraitTest для тестирования отображения доступных
      действий на страницах просмотра, создания, редактирования и списка сущностей.
    - SonataAdminTabTraitTest для тестирования вкладок.

Для запуска тестов сначала необходимо установить зависимости через composer

`docker-compose run php composer install`

После запустить тесты

`docker-compose run php vendor/bin/phpunit tests/`
