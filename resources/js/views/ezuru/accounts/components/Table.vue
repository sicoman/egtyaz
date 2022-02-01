<template>
    <el-row>
        <el-col :span="24">
<div id="PaymentsTable">
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">

                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>  

                    <el-table-column align="center" label="Status" width="50">
                        <template slot-scope="scope">
                            <span v-if="scope.row.booking_status">
                                <span v-if="scope.row.booking_status == 1"> Avilable </span>
                                <span v-else-if="scope.row.booking_status == 2"> Booked </span>
                                <span v-else>Unavilable</span>
                            </span>
                            <span v-else>{{ status[scope.row.status] }}</span>
                        </template>
                    </el-table-column>

                     <el-table-column align="center" label="Host" >
                        <template slot-scope="scope">
                            <div>
                                <span v-if="scope.row.owner">{{ scope.row.owner.id+'-'+scope.row.owner.name }}</span>
                                <span v-else >{{ scope.row.user_id }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Type - List type">
                        <template slot-scope="scope">
                            <div>
                                <span v-if="scope.row.category">{{ scope.row.category.id+'-'+scope.row.category.name }}</span>
                                <span v-else >{{ scope.row.type }}</span>
                                <span v-if="scope.row.type2">{{ scope.row.type2.id+'-'+scope.row.type2.name }}</span>
                                <span v-else >{{ scope.row.type2 }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column align="left" label="Data">
                        <template slot-scope="scope">
                            <div>
                                <el-row>
                                    <el-col :span="24"><span>{{ scope.row.rooms }} Rooms </span></el-col>
                                    <el-col :span="24"><span>{{ scope.row.beds }} Beds </span></el-col>
                                    <el-col :span="24"><span>{{ scope.row.bathrooms }} Bathroom </span></el-col>
                                    <el-col :span="24"><span>{{ scope.row.balacons }} Balacons </span></el-col>
                                </el-row>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column align="left" label="Country">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.country && scope.row.country.name">{{scope.row.country.name}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Government">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.goverement && scope.row.goverement.name">{{scope.row.goverement.name}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="City">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.city && scope.row.city.name">{{scope.row.city.name}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Area">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.area&& scope.row.area.name">{{scope.row.area.name}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="ZIP">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.zipcode">{{scope.row.zipcode}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Latitude">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.latitude">{{scope.row.latitude}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Longtude">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.longitude">{{scope.row.longitude}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Day-Price" prop="price"></el-table-column>

                    <el-table-column align="left" label="Booking Count">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.booking">{{scope.row.booking.times}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Price">
                           <template slot-scope="scope">
                                 <span v-if="scope.row.booking_cancel" :content="scope.row.booking_cancel.price">
                                     <span class="x" >{{scope.row.booking.price}}</span>
                                     <span v-if="scope.row.booking_cancel"  ><br/><b>{{ (scope.row.booking.price - scope.row.booking_cancel.price) }}</b></span>
                                 </span>
                                 <span  v-else-if="scope.row.booking">{{ scope.row.booking.price }}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Ezuru-Fee">
                            <template slot-scope="scope">
                                 <span v-if="scope.row.booking_cancel" :content="scope.row.booking_cancel.ezuru_fee">
                                     <span class="x" >{{scope.row.booking.ezuru_fee}}</span>
                                     <span v-if="scope.row.booking_cancel"  ><br/><b>{{ (scope.row.booking.ezuru_fee - scope.row.booking_cancel.ezuru_fee) }}</b></span>
                                 </span>
                                 <span  v-else-if="scope.row.booking">{{scope.row.booking.ezuru_fee}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="VAT">
                            <template slot-scope="scope">
                                 <span v-if="scope.row.booking_cancel" :content="scope.row.booking_cancel.tax">
                                     <span class="x" >{{scope.row.booking.tax}}</span>
                                     <span v-if="scope.row.booking_cancel"  ><br/><b>{{ (scope.row.booking.tax - scope.row.booking_cancel.tax) }}</b></span>
                                 </span>
                                 <span  v-else-if="scope.row.booking">{{scope.row.booking.tax}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Tourism">
                            <template slot-scope="scope">
                                 <span v-if="scope.row.booking_cancel" :content="scope.row.booking_cancel.tourism">
                                     <span class="x" >{{scope.row.booking.tourism}}</span>
                                     <span v-if="scope.row.booking_cancel"  ><br/><b>{{ (scope.row.booking.tourism - scope.row.booking_cancel.tourism) }}</b></span>
                                 </span>
                                 <span  v-else-if="scope.row.booking">{{scope.row.booking.tourism}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Avilable Days">
                            <template slot-scope="scope">
                                 <span  v-if="scope.row.avilable_days">{{scope.row.avilable_days[1]}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="unAvilable Days">
                            <template slot-scope="scope">
                                 <span  v-if="scope.row.avilable_days">{{scope.row.avilable_days[0]}}</span>
                           </template>
                    </el-table-column>

                    <el-table-column align="left" label="Booked Days">
                            <template slot-scope="scope">
                                 <span  v-if="scope.row.avilable_days">{{scope.row.avilable_days[2]}}</span>
                           </template>
                    </el-table-column>

                    
                </el-table>

    </div>
        </el-col>
    </el-row>
</template>

<script>
    import settings from '@/settings' ;
    import AccountsResource from '@/api/ezuru/accounts' ;
    const Accounts = new AccountsResource() ;

    const Vue = window.vue ;

    import permission from '@/directive/permission/index.js' ;

    export default {
        // directives : { permission } ,
        data(){
            return {
                sc : {} ,
                status : settings.unitStatus ,
                html : '',
            }
        },
        props : ['list' , 'loading' , 'reload'],
        methods : {
            reloadAgain() {
                this.$emit('reloadAgain') ;
            }
        },
        mounted(){
            
        }
    }
</script>

<style scoped>
span.x { 
        width: 100%;
        position: relative;
        margin-right:10px;    
}

span.x:before { content: '';
        position: absolute;
        bottom: 50%;
        border-bottom: 2px red solid;
        width: 100%;
        z-index:0;
}

</style>