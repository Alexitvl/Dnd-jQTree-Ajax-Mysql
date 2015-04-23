<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/tree.jquery.js"></script>      
        <link rel="stylesheet" href="/css/jqtree.css">        
    </head>
    <body>
        <div id="tree1"></div>
        <script type="text/javascript">
            //Для генерации дерева на стороне клиента используется jQtree плагин
            $(function () {
                $('#tree1').tree({
                    //Инициализируем дерево
                    dataUrl: '/php/treehandler.php?gettree=true',
                    dragAndDrop: true,
                    onCreateLi: function (node, $li) {
                        //Задаем идентификаторы для узлов дерева
                        node.parent_id = (typeof node.parent_id == "undefined") ? 0 : node.parent.id;
                        node.level = node.getLevel();
                        if (node.is_node == 1) {
                            $li.find('.jqtree-title').before('<span class="icon-node icon"></span>');
                            $li.attr('id', "node" + node.id).attr('class');

                        }
                        else {
                            $li.find('.jqtree-title').before('<span class="icon-file icon"></span>');
                            $li.attr('id', "file" + node.id);
                            $li.addClass('file');
                        }
                    },
                    onCanMoveTo: function (moved_node, target_node, position) {
                        //Определяем правила для перетаскивания узлов дерева
                        if (target_node.is_node == 0) {
                            return (position == 'after');
                        }
                        else if (target_node.id > 0) {
                            return true;
                        } if (target_node.id == 0) {
                            return (position == 'inside');
                        } 

                    }
                });
            });
            //Инициализируем обработчик событий перетаскивания 
            $('#tree1').bind(
                    'tree.move',
                    function (event) {
                        //Дожидаемся пока действие будет выполнено полностью
                        event.preventDefault();
                        event.move_info.do_move();
                        //Обновляем данные узлов после перетаскивания
                        var ids = $('li.jqtree_common').map(function (index) {
                            $id = this.id;
                            return $id.replace(/[^\d]/gi, '');
                        });
                        for (var i = 0; i < ids.length; i++) {
                               
                            var node = $(this).tree('getNodeById', ids[i]);
                            $(this).tree(
                                    'updateNode',
                                    node,
                                    {
                                        label: node.name,
                                        id: node.id,
                                        is_node: node.is_node,
                                        is_open: (node.is_open != 1) ? 0 : 1,
                                        parent_id: (node.parent_id < 1) ? 0 : node.parent.id,
                                        index_node: $(node.element).index(),
                                        level: node.getLevel(),
                                    }
                            );
                        } 
                        //Запускаем прогресс бар перед отправкой на сервер заменяя иконку узла
                        $('#node' + event.move_info.moved_node.id).find('.icon').attr('class', 'icon-loader');
                        $.ajax({
                            type: 'GET',
                            dataType: 'text',
                            url: '/php/treehandler.php',
                            data: {"savetree": $(this).tree('toJson')},
                            success: function (data) {
                                //После успешной отправки на сервер возвращаем исходную иконку узла или выводим ошибку, если пришла ошибка при сохранении в базу
                                if (data == 'saved') {
                                    if (node.is_node)
                                        $('#node' + event.move_info.moved_node.id).find('.icon-loader').attr('class', 'icon-node');
                                    else
                                        $('#file' + event.move_info.moved_node.id).find('.icon-loader').attr('class', 'icon-file');
                                } else
                                    alert("Ошибка при сохранении в базу данных!");
                            }

                        });
                    }
            );
        </script>
    </body>
</html>
