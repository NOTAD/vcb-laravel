<template>
    <div class="sidebar" data-active-color="green" data-background-color="black">
        <div class="logo">
            <a :href="this.route('index')">
                <!--<img src="/images/admin/logo.png" alt="Logo">-->AutoBankSystem
            </a>
        </div>
        <div class="sidebar-wrapper">
            <div class="user">
                <div class="photo">
                    <img :src="user.image" :alt="user.name" />
                </div>
                <div class="info text-middle">
                    <a href="#"><span>{{ user.name }}</span></a>
                </div>
            </div>
            <ul class="nav">
                <li v-for="menu in menues" :key="menu.name" :class="activeRoute === menu.name ? 'active' : ''">
                    <a :href="menu.route">
                        <i class="material-icons">{{ menu.icon }}</i>
                        <p> {{ menu.label }} </p>
                    </a>
                </li>
                <template v-if="hasRole(systemRoleId)">
                    <li v-for="menu in systemMenues" :key="menu.name" :class="activeRoute === menu.name ? 'active' : ''">
                        <a :href="menu.route">
                            <i class="material-icons">{{ menu.icon }}</i>
                            <p> {{ menu.label }} </p>
                        </a>
                    </li>
                </template>

            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            user: {
                Type: Object,
                default() {
                    return {};
                },
            },
            systemRoleId: {
                Type: String,
                default: '',
            },
            activeRoute: {
                Type: String,
                default: '',
            }
        },
        data() {
           return {
               menues: [
                   {
                       name: 'admin.banks_index',
                       route: this.route('admin.banks_index'),
                       label: 'Ng??n h??ng',
                       icon: 'description',
                   },
                   {
                       name: 'admin.history_transfers_index',
                       route: this.route('admin.history_transfers_index'),
                       label: 'L???nh chuy???n kho???n',
                       icon: 'library_books',
                   },
                   {
                       name: 'admin.files_index',
                       route: this.route('admin.files_index'),
                       label: 'Th?? vi???n ???nh',
                       icon: 'image',
                   },
                   {
                       name: 'admin.users_index',
                       route: this.route('admin.users_index'),
                       label: 'Ng?????i d??ng',
                       icon: 'people',
                   },
                   {
                       name: 'admin.setting_index',
                       route: this.route('admin.setting_index'),
                       label: 'C??i ?????t',
                       icon: 'settings',
                   },
               ],
               systemMenues: [
                   {
                       name: 'admin.admins_index',
                       route: this.route('admin.admins_index'),
                       label: 'Qu???n tr??? vi??n',
                       icon: 'supervised_user_circle',
                   },
                   {
                       name: 'admin.roles_index',
                       route: this.route('admin.roles_index'),
                       label: 'Ph??n quy???n',
                       icon: 'accessibility',
                   }
               ],
           };
       },
       methods: {
           hasRole(roleId) {
               const role = this._.find(this.user.roles, role => role.id.toString() === roleId.toString());
               return !this._.isEmpty(role);
           },

       },
    }
</script>
