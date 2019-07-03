<?php

?>

<aside class="main-sidebar">
    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    [
                        'label' => '权限管理',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => '角色管理', 'icon' => 'file-code-o', 'url' => ['rbac/role-index']],
                        ],
                    ],
                ],
            ]
        ) ?>
    </section>
</aside>
