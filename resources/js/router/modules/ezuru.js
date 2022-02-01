/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const UserRoutes = {
    path: '/users',
    component: Layout,
    name: 'EzuruUsers',
    alwaysShow: true,
    meta: {
        title: 'Users',
        icon: 'user',
        permissions: ['view menu users'],
    },
    children: [{  
            path: 'admins',
            component: () =>
                import ('@/views/users/List'),
            name: 'admins',
            meta: { title: 'Admin - Supervisiors', icon: 'people', permissions: ['view menu admin'], 'role': 'admin,manager,editor' },
        },
        {
            path: 'admin/edit/:id(\\d+)',
            component: () =>
                import ('@/views/users/Profile'),
            name: 'adminProfile',
            meta: { title: 'Admin Profile', noCache: true, permissions: ['manage admin edit'] },
            hidden: true,
        },
        {
            path: 'members',
            component: () =>
                import ('@/views/ezuru/users/List'),
            name: 'users',
            meta: { title: 'Users List', icon: 'user', permissions: ['view menu user'], 'role': 'user' },
        },
        {
            path: 'user/edit/:id(\\d+)',
            component: () =>
                import ('@/views/ezuru/users/profile/profile'),
            name: 'UserProfile',
            meta: { title: 'userProfile', noCache: true, permissions: ['manage user edit'] },
            hidden: true,
        },
        {
            path: 'roles',
            component: () =>
                import ('@/views/role-permission/List'),
            name: 'RoleList',
            meta: { title: 'rolePermission', icon: 'role', permissions: ['view menu premissions'] },
        },
        {
            path: 'reporting',
            component: () =>
                import ('@/views/ezuru/flags/flags'),
            name: 'Reporting',
            meta: { title: 'User Reporting', icon: 'edit', noCache: true, permissions: ['view menu flags users'], 'type': 'user' },
        },{
            path: 'Stages',
            component: () =>
                import ('@/views/ezuru/taxonomy/taxonomy'),
            name: 'Stages',
            meta: { title: 'Stages', noCache: true, permissions: ['view menu stages categories'], 'type': 'student_stage' },
        }
    ]
};

const MainRoutes = {
    path: '/',
    component: Layout,
    name: 'EzuruMain',
    alwaysShow: true,
    meta: {
        title: 'Main Features',
        icon: 'dashboard',
        permissions: ['view menu users'],
    },
    children: [
        {
            path: 'packages',
            component: () =>
                import ('@/views/ezuru/packages/packages'),
            name: 'Packages',
            meta: { title: 'Packages', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'packages' },
        },
        {
            path: 'copouns',
            component: () =>
                import ('@/views/ezuru/copouns/copouns'),
            name: 'Copouns',
            meta: { title: 'Copouns', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'copouns' },
        }
    ]
}

const QuestionsRoutes = {
    path: '/set-questions',
    component: Layout,
    name: 'EzuruQuestions',
    alwaysShow: true,
    meta: {
        title: 'Questions',
        icon: 'dashboard',
        permissions: ['view menu users'],
    },
    children: [
        {
            path: 'questions',
            component: () =>
                import ('@/views/ezuru/questions/questions'),
            name: 'Questions',
            meta: { title: 'Questions', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'questions' },
        },
        {
            path: 'questions/edit/:id(\\d+)',
            component: () =>
                import ('@/views/ezuru/questions/add'),
            name: 'Edit question',
            meta: { title: 'Edit question', noCache: true, permissions: ['manage exams edit'] },
            hidden: true,
        },
        {
            path: 'category',
            component: () =>
                import ('@/views/ezuru/taxonomy/taxonomy'),
            name: 'Categories', 
            meta: { title: 'Categories', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'category' },
        },
        {
            path: 'subject',
            component: () =>
                import ('@/views/ezuru/taxonomy/taxonomy'),
            name: 'Subjects', 
            meta: { title: 'Subjects', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'subject' },
        },
        {
            path: 'skill',
            component: () =>
                import ('@/views/ezuru/taxonomy/taxonomy'),
            name: 'Skills', 
            meta: { title: 'Skills', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'skill' },
        },
        {
            path: 'level',
            component: () =>
                import ('@/views/ezuru/taxonomy/taxonomy'),
            name: 'Levels', 
            meta: { title: 'Levels', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'level' },
        },
        {
            path: 'attachments',
            component: () => import ('@/views/ezuru/post/post'),
            name: 'Question Attachment',
            meta: { title: 'Question Attachment', noCache: true, permissions: ['view menu questions'], 'type': 'attachments' },
        }
    ]
}

const ExamRoutes = {
        path: '/questions',
        component: Layout,
        name: 'EzuruExams',
        affix: false,
        alwaysShow: false,
        meta: {
            title: 'Exams',
            icon: 'edit',
            permissions: ['view menu content'],
        },
        children: [
            {
                path: 'exams',
                component: () =>
                    import ('@/views/ezuru/exams/exams'),
                name: 'Students Exams', 
                meta: { title: 'Exams', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'free' },
            },
            {
                path: 'mock',
                component: () =>
                    import ('@/views/ezuru/exams/exams'),
                name: 'Mock Tests', 
                meta: { title: 'Mock Tests', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'mock' },
            },
            {
                path: 'challenge',
                component: () =>
                    import ('@/views/ezuru/exams/exams'),
                name: 'Challenges', 
                meta: { title: 'Challenges', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'challenge' },
            },
            {
                path: 'competitions',
                component: () =>
                    import ('@/views/ezuru/post/comp'),
                name: 'competitions', 
                meta: { title: 'competitions', icon: 'edit', noCache: true, permissions: ['view menu users'], 'type': 'competitions' },
            },
            {
                path: 'exams/show/:id(\\d+)',
                component: () =>
                    import ('@/views/ezuru/exams/show'),
                name: 'Show Exam',
                meta: { title: 'Show Exam', noCache: true, permissions: ['manage exams edit'] },
                hidden: true,
            },
            {
                path: 'exams/edit/:id(\\d+)',
                component: () =>
                    import ('@/views/ezuru/exams/add'),
                name: 'Edit Exam',
                meta: { title: 'Edit Exam', noCache: true, permissions: ['manage exams edit'] },
                hidden: true,
            },
            {
                path: 'exams/add',
                component: () =>
                    import ('@/views/ezuru/exams/add'),
                name: 'Add Exam',
                meta: { title: 'Add New Exam', noCache: true, permissions: ['manage exams edit'] },
                hidden: true,
            }
        ]
    };


const ContentRoutes = {
    path: '/content',
    component: Layout,
    name: 'EzuruContent',
    affix: false,
    alwaysShow: false,
    meta: {
        title: 'Content',
        icon: 'edit',
        permissions: ['view menu content'],
    },
    children: [
        {
            path: 'course',
            component: () =>
                import ('@/views/ezuru/child'),
            name: 'Course',
            alwaysShow: true,
            meta: { title: 'Courses', icon: 'edit', noCache: true, permissions: ['view menu news'], 'type': 'course' },
            children: [{
                path: 'pages',
                component: () =>
                    import ('@/views/ezuru/course/course'),
                name: 'List Courses',
                meta: { title: 'List Courses', noCache: true, permissions: ['view menu news pages'], 'type': 'courses' },
            }, {
                path: 'category',
                component: () =>
                    import ('@/views/ezuru/taxonomy/taxonomy'),
                name: 'Categorys',
                meta: { title: 'Categorys', noCache: true, permissions: ['view menu news categories'], 'type': 'courses' },
            }
            ]
        },
        {
            path: 'news',
            component: () =>
                import ('@/views/ezuru/child'),
            name: 'News',
            alwaysShow: true,
            meta: { title: 'News', icon: 'edit', noCache: true, permissions: ['view menu news'], 'type': 'news' },
            children: [{
                path: 'pages',
                component: () =>
                    import ('@/views/ezuru/post/post'),
                name: 'List News',
                meta: { title: 'List News', noCache: true, permissions: ['view menu news pages'], 'type': 'news' },
            }, {
                path: 'category',
                component: () =>
                    import ('@/views/ezuru/taxonomy/taxonomy'),
                name: 'Categorys',
                meta: { title: 'Categorys', noCache: true, permissions: ['view menu news categories'], 'type': 'news' },
            }, {
                path: 'settings',
                component: () =>
                    import ('@/views/ezuru/settings/static'),
                name: 'Settings',
                alwaysShow: true,
                meta: { title: 'Settings', icon: 'edit', noCache: true, permissions: ['view menu news settings'], 'type': 'news' },
            }]

        },
         {
            path: 'forum',
            component: () =>
                import ('@/views/ezuru/child'),
            name: 'Forum',
            alwaysShow: true,
            meta: { title: 'Forum', icon: 'edit', noCache: true, permissions: ['view menu forum'], 'type': 'forum' },
            children: [
                {
                    path: 'forum', 
                    component: () => import ('@/views/ezuru/post/post'),
                    name: 'Posts' ,
                    alwaysShow: true ,
                    meta: { title: 'Posts', icon: 'edit', noCache: true, permissions: ['view menu forum'], 'type': 'forum' },
                },
                {
                    path: 'category',
                    component: () =>
                        import ('@/views/ezuru/taxonomy/taxonomy'),
                    name: 'Categorys',
                    meta: { title: 'Categorys', noCache: true, permissions: ['view menu forum'], 'type': 'forum' },
                },
                {
                    path: 'comments',
                    component: () =>
                        import ('@/views/ezuru/comment/comments'),
                    name: 'Forum Comments',
                    meta: { title: 'Forum Comments' , noCache: true, permissions: ['view menu forum'], 'type': 'forum' },
                },
                {
                    path: 'settings',
                    component: () =>
                        import ('@/views/ezuru/settings/static'),
                    name: 'Forum Settings',
                    alwaysShow: true,
                    meta: { title: 'Forum Settings', icon: 'edit', noCache: true, permissions: ['view menu forum'], 'type': 'forum' },
                }
            ]

        },
        {
            path: 'Content Pages',
            component: () =>
                import ('@/views/ezuru/child'),
            name: 'Content Pages',
            alwaysShow: true,
            meta: { title: 'Content Pages', icon: 'edit', noCache: true, permissions: ['view menu pages'], 'type': 'pages' },
            children: [{
                path: 'pages',
                component: () =>
                    import ('@/views/ezuru/post/post'),
                name: 'Pages',
                meta: { title: 'Pages', noCache: true, permissions: ['view menu pages'], 'type': 'pages' },

            }, {
                path: 'settings',
                component: () =>
                    import ('@/views/ezuru/settings/static'),
                name: 'Settings',
                alwaysShow: true,
                meta: { title: 'Settings', icon: 'edit', noCache: true, permissions: ['view menu pages'], 'type': 'pages' },
            }
        ]

        },
        {
            path: 'E-learning Posts',
            component: () =>
                import ('@/views/ezuru/child'),
            name: 'E-learning',
            alwaysShow: true,
            meta: { title: 'E-learning', icon: 'edit', noCache: true, permissions: ['view menu pages'], 'type': 'elearn' },
            children: [{
                path: 'pages',
                component: () =>
                    import ('@/views/ezuru/post/popular'),
                name: 'Pages',
                meta: { title: 'Pages', noCache: true, permissions: ['view menu learn'], 'type': 'learn' },
            },
            {
                path: 'posts',
                component: () =>
                    import ('@/views/ezuru/post/post'),
                name: 'Posts',
                meta: { title: 'Posts', noCache: true, permissions: ['view menu elearn'], 'type': 'elearn' },
            },
            {
                path: 'category',
                component: () =>
                    import ('@/views/ezuru/taxonomy/taxonomy'),
                name: 'Categorys',
                meta: { title: 'Categorys', noCache: true, permissions: ['view menu elearn'], 'type': 'elearn' },
            },
            {
                path: 'comments',
                component: () =>
                    import ('@/views/ezuru/comment/comments'),
                name: 'Comments',
                meta: { title: ' Comments' , noCache: true, permissions: ['view menu elearn'], 'type': 'elearn' },
            }, {
                path: 'settings',
                component: () =>
                    import ('@/views/ezuru/settings/static'),
                name: 'Settings',
                alwaysShow: true,
                meta: { title: 'Settings', icon: 'edit', noCache: true, permissions: ['view menu elearn'], 'type': 'elearn' },
            }
        ]

        }
    ]
}; 

const PaymentRoutes = {
    path: '/ezuru/payments',
    component: Layout ,
    name: 'EzuruPayments',
    alwaysShow: true,
    meta: {
        title: 'Payments',
        icon: 'dollar',
        permissions: ['view menu users'],
    },
    children: [{
        path: 'Subscripations',
        component: () =>
            import ('@/views/ezuru/payments/payments'),
        name: 'List Subscripations',
        meta: {
            title: ' Subscripations',
            icon: 'qq',
            permissions: ['view menu users'] ,
            type : 'package'
        },
    },
    {
        path: 'courses',
        component: () =>
            import ('@/views/ezuru/payments/payments'),
        name: ' Courses',
        meta: {
            title: ' Courses',
            icon: 'qq',
            permissions: ['view menu users'] ,
            type : 'course'
        },
    },
    ]
};

const flagsRoutes = {
    path: '/flags',
    component: () =>
        import ('@/views/ezuru/child'),
    name: 'EzuruFlags',
    hidden: true,
    alwaysShow: true,
    affix: false,
    meta: {
        title: 'Flags',
        icon: 'skill',
        permissions: ['view menu flags'],
    },
    children: []
};

const LogRoutes = {
    path: '/log',
    component: () =>
        import ('@/views/ezuru/log/log'),
    name: 'EzuruLog',
    alwaysShow: true,
    meta: {
        title: 'Log',
        icon: 'admin',
        permissions: ['view menu log'],
    }
};



const BadgeRoutes = {
    path: '/badges',
    component: () =>
                import ('@/views/ezuru/taxonomy/taxonomy'),
    name: 'EzuruBadges',
    alwaysShow: true,
    meta: {
        title: 'User Badges',
        icon: 'admin',
        permissions: ['view menu holidays'],
        type: 'user_badge'
    },
    childrenx: [{
            path: '/user',
            component: () =>
                import ('@/views/ezuru/taxonomy/taxonomy'),
            name: 'EzuruBadgesUsers',
            alwaysShow: true,
            meta: {
                title: 'Badges',
                icon: 'admin',
                permissions: ['view menu badges'],
                type: 'user_badge'
            }
        }
    ]
};



const HelpersRoutes = {
    path: '/helpers',
    component: Layout,
    name: 'EzuruHelpers',
    alwaysShow: false,
    affix: false,
    meta: {
        title: 'Helpers',
        icon: 'example',
        permissions: ['view menu helpers'],
    },
    children: [
        flagsRoutes,
        BadgeRoutes,
        LogRoutes ,
        {
            path: 'callus' ,
            component: () =>
                import ('@/views/ezuru/contact/contact'),
            name: 'Contact us messages',
            meta: {
                title: 'Contact us messages',
                icon: 'email',
                permissions: ['view menu comments'],
                'type' : 'callus'
            },
            
        },
        {
            path: 'comments',
            component: () =>
                import ('@/views/ezuru/comment/comments'),
            name: 'Comments',
            meta: {
                title: 'Comments' ,
                icon: 'comment' ,
                permissions: ['view menu comments'],
            }
        },
        {
            path: 'tickets' ,
            component: () =>
                import ('@/views/ezuru/tickets/tickets'),
            name: 'tickets',
            meta: {
                title: 'Tickets',
                icon: 'email',
                permissions: ['view menu comments'],
                'type' : 'tickets'
            },
            
        },
    ]
};

const SettingsRoutes = {
    path: '/settings',
    component: Layout,
    name: 'EzuruSettings',
    alwaysShow: true,
    meta: {
        title: 'Settings',
        icon: 'admin',
        permissions: ['view menu settings'],
    },
    children: [{
        path: 'site',
        component: () =>
            import ('@/views/ezuru/settings/settings'),
        name: 'Seo Settings',
        meta: {
            title: 'Site Settings',
            icon: 'qq',
            permissions: ['view menu settings seo'],
            type: 'site'
        }
    },{
        path: 'seo',
        component: () =>
            import ('@/views/ezuru/settings/settings'),
        name: 'Seo Settings',
        meta: {
            title: 'Seo Settings',
            icon: 'qq',
            permissions: ['view menu settings seo'],
            type: 'seo'
        }
    }, {
        path: 'social',
        component: () =>
            import ('@/views/ezuru/settings/settings'),
        name: 'Social Settings',
        meta: {
            title: 'Social Settings',
            icon: 'qq',
            permissions: ['view menu settings social'],
            type: 'social'
        }
    },
    {
        path: 'homepage',
        component: () =>
            import ('@/views/ezuru/settings/static'),
        name: 'Homepage Settings',
        meta: {
            title: 'Homepage Settings',
            icon: 'qq',
            permissions: ['view menu settings social'],
            type: 'homepage'
        }
    },
    {
        path: 'contact',
        component: () =>
            import ('@/views/ezuru/settings/static'),
        name: 'Homepage Settings',
        meta: {
            title: 'Contact us Settings',
            icon: 'qq',
            permissions: ['view menu settings social'],
            type: 'contact'
        }
    },
    {
        path: 'Dashboard',
        component: () =>
            import ('@/views/ezuru/settings/static'),
        name: 'Dashboard Settings',
        meta: {
            title: 'Dashboard Settings',
            icon: 'qq',
            permissions: ['view menu settings social'],
            type: 'dashboard'
        }
    }, {
        path: 'hidden',
        component: () =>
            import ('@/views/ezuru/settings/settings'),
        name: 'Hidden Settings',
        meta: {
            title: 'Hidden Settings',
            icon: 'qq',
            permissions: ['view menu settings hidden'],
            type: 'hidden'
        }
    }]
};

const LangRoutes = {
    path: '/languages',
    component: Layout,
    name: 'EzuruLang',
    hidden: false,
    alwaysShow: true,
    meta: {
        title: 'Languages Manager',
        icon: 'admin',
        permissions: ['view menu reports'],
    },
    children: [{
        path: 'ar',
        component: () =>
            import ('@/views/ezuru/languages/lang'),
        name: 'Arabic',
        meta: {
            title: 'Arabic Languages',
            icon: 'qq',
            permissions: ['view menu reports'],
            type: 'ar'
        }
    },
    {
        path: 'en',
        component: () =>
            import ('@/views/ezuru/languages/lang'),
        name: 'English',
        meta: {
            title: 'English Languages',
            icon: 'qq',
            permissions: ['view menu reports'],
            type: 'en'
        }
    }]
};

const NotificationsRoutes = {
    path: '/notifications',
    component: Layout,
    name: 'EzuruNotifications',
    alwaysShow: true,

    meta: {
        title: 'Notifications',
        icon: 'admin',
        permissions: ['view menu notifications'],
    },
    children: [{
            path: 'email',
            component: () =>
                import ('@/views/ezuru/child'),
            name: 'Email Templates',
            meta: {
                title: 'Email Templates',
                icon: 'qq',
                permissions: ['view menu notifications emails'],
                type: 'email'
            },
            children: [{
                    path: 'Messages',
                    component: () =>
                        import ('@/views/ezuru/settings/static'),
                    name: 'Email Templates',
                    meta: {
                        title: ' Email Templates',
                        icon: 'qq',
                        permissions: ['view menu notifications emails'],
                        type: 'notifications_email'
                    },
                },
                {
                    path: 'Messages_title',
                    component: () =>
                        import ('@/views/ezuru/settings/static'),
                    name: 'Templates title',
                    meta: {
                        title: 'Templates title',
                        icon: 'qq',
                        permissions: ['view menu notifications emails'],
                        type: 'notifications_email_title'
                    },
                }
            ]
        },
        {
            path: 'sms',
            component: () =>
                import ('@/views/ezuru/child'),
            name: 'SMS Templates',
            meta: {
                title: 'SMS Templates',
                icon: 'qq',
                permissions: ['view menu notifications sms'],
                type: 'sms'
            },
            children: [{
                    path: 'messages',
                    component: () =>
                        import ('@/views/ezuru/settings/static'),
                    name: 'SMS Templates',
                    meta: {
                        title: 'Messages Templates',
                        icon: 'qq',
                        permissions: ['view menu notifications sms'],
                        type: 'notifications_sms'
                    },
                }
                
            ]
        },
        {
            path: 'via',
            component: () =>
                import ('@/views/ezuru/settings/static'),
            name: 'Notifications Send Handeler',
            meta: {
                title: 'Notification Controllers',
                icon: 'qq',
                permissions: ['view menu notifications controller'],
                type: 'notifications_via'
            }
        },
        {
            path: 'custom',
            component: () =>
                import ('@/views/ezuru/notification/custom'),
            name: 'Custom Notification',
            meta: {
                title: 'Custom Notification',
                icon: 'qq',
                permissions: ['view menu notifications controller'],
                type: ''
            }
        }
    ]
};


export { HelpersRoutes };
export { SettingsRoutes };
export { UserRoutes };
export { ContentRoutes };
export { LangRoutes };
export { QuestionsRoutes };
export { PaymentRoutes } ;
export { ExamRoutes } ;
export { NotificationsRoutes } ;
export default MainRoutes;