import variables from '@/styles/element-variables.scss';

export default {
    /**
     * @type {String}
     */
    title: 'Laravel Vue Admin',
    theme: variables.theme,

    /**
     * @type {boolean} true | false
     * @description Whether show the settings right-panel
     */
    showSettings: false,

    /**
     * @type {boolean} true | false
     * @description Whether need tagsView
     */
    tagsView: true,

    /**
     * @type {boolean} true | false
     * @description Whether fix the header
     */
    fixedHeader: true,

    /**
     * @type {boolean} true | false
     * @description Whether show the logo in sidebar
     */
    sidebarLogo: true,

    /**
     * @type {string | array} 'production' | ['production','development']
     * @description Need show err logs component.
     * The default is only used in the production env
     * If you want to also use it in dev, you can pass ['production','development']
     */
    errorLog: 'production',

    apiUrl: '/api/',

    backend: '/',

    'roles' : ['admin' , 'student' , 'teacher' , 'support' , 'editor'] ,

    'adminRoles' : ['admin' , 'support' , 'editor'] ,

    'nonAdminRoles' : ['student'] ,
    
    packages : {
        'bank' : 'بنك الأسئلة' ,
        'mock_exam' : 'اختبار قدرات' ,
        'free_exam' : 'الاختبارات التجريبية' ,
        'videos' : 'الفيديوهات التعليمية' ,
        'foundation' : 'التأسيس والمهارات' ,
        'challenges' : 'المسابقات' ,
        'comunity' : 'مجتمع المنصة' ,
        'courses' : 'الدورات المتقدمة ' ,
        'ask_teacher' : 'اسأل معلم' ,
    } ,
    taxonomy: {
        
        category: {
            photo: true,
            parent: false ,
            description: false,
        },
        subject: {
            photo: true ,
            parent: 'category',
            description: false,
        },
        skill: {
            photo: true,
            parent: 'subject',
            description: false,
        },
        
        user_badge: {
            photo: true,
            parent: false,
            description: false,
        },
        unit_badge: {
            photo: true,
            parent: false,
            description: false,
        },
    },
    posts: {
        articles: {
            photo: false,
            taxonomy: false,
            description: false,
        },
        forum: {
            photo: false,
            tinymce: true,
            taxonomy: 'forum',
            description: true,
            author: true,
        },
        learn: {
            photo: true,
            file : false ,
            tinymce: false,
            taxonomy: false,
            parent: false ,
            description: true,
            author: false ,
        },
        elearn: {
            photo: true,
            file : true ,
            tinymce: true,
            taxonomy: 'elearn',
            parent: 'learn' ,
            description: true ,
            author: false ,
        },
        pages: {
            photo: true,
            tinymce: true,
            taxonomy: false,
            description: true,
            author: false,
        },
        attachments: {
            photo: false,
            tinymce: true,
            taxonomy: false,
            description: true,
            author: false,
        },
        slider: {
            taxonomy: false,
        },
        competitions :  {
            taxonomy : false,
            author : false ,
        }, 
        checkin: {
            taxonomy: false,
        },
        blog: {
            tinymce: true,
            taxonomy: false,
        },
        news: {
            tinymce: true,
            taxonomy: 'news',
        },
        cancle: {
            tinymce: true,
            taxonomy: false,
        },
        popular_places: {
            tinymce: true,
            taxonomy: false,
        },
        why_ezuru : {
            taxonomy: false,
        },
        how_work : {
            taxonomy: false,
        },
        team : {
            taxonomy: false,
        },
        about : {
            taxonomy: false,
        },
        payment_methods :{
            taxonomy: false,
        }
    },
    userStatus: {
        '0': 'Disabled',
        '1': 'Active',
    },
    reportStatus: {
        '0': 'Disabled',
        '1': 'Active',
    },
    unitStatus: {
        '0': 'Disabled',
        '1': 'Active',
        '2': 'Updated',
        '-10': 'Draft',
    },
    unitFeature: {
        '0': 'Not Featured',
        '1': 'Featured',
    },
    paymentStatus: {
        '0': 'Not Paid',
        '1': 'Paid',
        '-1': 'Cancled',
    },
    bookingStatus: {
        '6': 'Key Delivered',
        '5': 'Confirm Key Delivered',
        '4': 'Checkout',
        '3': 'Checkin',
        '2': 'Paid',
        '1': 'Approved',
        '0': 'Waiting  Approval',
        '-1': 'Closed - Expired',
        '-2': 'Cancel Request',
        '-3': 'Cancel',
        '-4': 'Booking rejected',
        '-5': 'UnPaid - Need Payment',
    },
    paymentGateways: {
        paypal: 'Paypal',
        visa: 'Visa',
        points: 'Points'
    },
    reviewsStatus: {
        '0': 'Disabled',
        '1': 'Active',
    },
    flagStatus: {
        '0': 'Disabled',
        '1': 'Active',
    },
    unitReviews: {
        '0': 'Accuracy of data added to the application',
        '1': 'Communication',
        '2': 'Cleanliness',
        '3': 'Comfort',
        '4': 'Location',
        '5': 'Amenities',
        '6': 'Check-in and Check-out',
        '7': 'Value for money',
    },
    guestReviews: {
        '0': 'Accuracy of verified data',
        '1': 'Communication',
        '2': 'Following the unit rules',
        '3': 'Check-in and Check-out',
    },
    defaultAvatar: 'https://4elements.md/wa-data/public/shop/themes/dsv2/img/dummy200.png',
    packagePeriods : {
        7 : 'Week' ,
        14 : '2 Weeks' ,
        21 : '3 Weeks' ,
        30 : '1 Month' ,
        60 : '2 Months' ,
        90 : '3 Months' ,
        120 : '4 Months' ,
        150 : '5 Months' ,
        180 : '6 Months' ,
        210 : '7 Months' ,
        240 : '8 Months' ,
        271 : '9 Months' ,
        302 : '10 Months' ,
        333 : '11 Months' ,
        365 : '1 Year' ,
        730 : '2 Years' ,
    }
};
