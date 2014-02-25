<div class="ipAdminPages" ng-app="Pages" ng-controller="ipPages">
    <div class="_container _languages ipsLanguages" ng-cloak>
        <div class="_actions">
<!--            <a href="#" class="btn btn-new"><i class="fa fa-plus"></i></a>-->
        </div>
        <ul>
            <li ng-repeat="language in languageList" ng-class="{active: language == activeLanguage}">
                <a href="" ng-click="setLanguageHash(language)">{{language.abbreviation}}</a>
            </li>
            <li>
                <a href="<?php echo $languagesUrl; ?>"><i class="fa fa-cog"></i></a>
            </li>
        </ul>
    </div>
    <div class="_container _menus ipsMenus" ng-cloak>
        <div class="_actions">
            <button ng-click="addMenuModal()" class="btn btn-new" role="button">
                <i class="fa fa-plus"></i>
                <?php _e('Add', 'ipAdmin'); ?>
            </button>
        </div>
        <ul class="ipsMenuList">
            <li ng-repeat="menu in menuList" menulist-post-repeat-directive data-menuname="{{menu.alias}}" data-menuid="{{menu.id}}" ng-class="{active: menu == activeMenu}">
                <a href="" ng-show="activeLanguage.code == menu.languageCode" ng-click="setMenuHash(menu)">{{menuTitle(menu)}}</a>
                <button class="btn btn-default _control" ng-click="updateMenuModal(menu)"><i class="fa fa-cog"></i></button>
            </li>
        </ul>
    </div>
    <div class="_container _pages ipsPagesContainer" ng-cloak>
        <div ng-repeat="menu in menuList" class="tree" ng-show="menu.id == activeMenu.id">
            <div id="pages_{{menu.languageCode}}_{{menu.alias}}">
                <div class="_actions">
                    <button class="btn btn-new ipsAddPage" ng-click="addPageModal()" role="button">
                        <i class="fa fa-plus"></i>
                        <?php _e('Add', 'ipAdmin'); ?>
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-default" title="<?php _e('Cut', 'ipAdmin'); ?>" ng-click="cutPage()" ng-class="{disabled: !selectedPageId}" role="button">
                            <i class="fa fa-cut"></i>
                        </button>
                        <button class="btn btn-default" title="<?php _e('Copy', 'ipAdmin'); ?>" ng-click="copyPage()" ng-class="{disabled: !selectedPageId}" role="button">
                            <i class="fa fa-copy"></i>
                        </button>
                        <button class="btn btn-default" title="<?php _e('Paste', 'ipAdmin'); ?>" ng-click="pastePage()" ng-class="{disabled: !copyPageId && !cutPageId}" role="button">
                            <i class="fa fa-paste"></i>
                        </button>
                    </div>
                </div>
                <div class="ipsPages"></div>
            </div>
        </div>
    </div>
<div class="_container _properties ipsProperties" ng-show="selectedPageId"></div>
<?php echo ipView('Ip/Internal/Pages/view/addPageModal.php', $this->getVariables())->render(); ?>
<?php echo ipView('Ip/Internal/Pages/view/addMenuModal.php', $this->getVariables())->render(); ?>
<?php echo ipView('Ip/Internal/Pages/view/updateMenuModal.php', $this->getVariables())->render(); ?>
