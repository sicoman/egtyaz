<template>
    <div id="unitTable">
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Title">
                        <template slot-scope="scope">
                            <span>{{ scope.row.title }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Price">
                        <template slot-scope="scope">
                            <span>{{ scope.row.price }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Type">
                        <template slot-scope="scope">
                        <span v-if="scope.row.type != null">
                            {{ scope.row.type.name }} 
                            <small v-if="scope.row.type2!=null">{{ scope.row.type2.name }}</small>
                         </span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Owner">
                        <template slot-scope="scope" v-if="scope.row.user">
                        <span>{{ scope.row.user.name }}</span>
                        <br />
                        <el-button-group>
                            <el-tooltip :content="scope.row.user.email" placement="top"  v-if="scope.row.user.email" >
                                <el-button size="mini" type="primary" icon="el-icon-message"></el-button>
                            </el-tooltip>

                            <el-tooltip :content="scope.row.user.mobile" placement="top"  v-if="scope.row.user.mobile" >
                                <el-button size="mini" type="primary" icon="el-icon-mobile"></el-button>
                            </el-tooltip>

                            <el-tooltip :content="scope.row.user.photoid" placement="top"  v-if="scope.row.user.photoid" >
                                <el-button size="mini" type="primary" @click="window.open( backend+''+scope.row.user.photoid )" icon="el-icon-edit"></el-button>
                            </el-tooltip>
                        </el-button-group>

                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="City">
                        <template slot-scope="scope">
                            <span v-if="scope.row.city">{{ scope.row.city.name }}
                                <i v-if="scope.row.city.hasOwnProperty('father')"> - {{ scope.row.city.father.name }}</i>
                            </span>
                            <small v-if="scope.row.address"><br/>{{ scope.row.address }}</small>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Features.">
                        <template slot-scope="scope">
                            <el-button-group>
                                <el-tooltip :content="' Rooms : '+scope.row.rooms" placement="top"  v-if="scope.row.rooms > 0" >
                                    <el-button size="mini" type="primary" icon="el-icon-menu"></el-button>
                                </el-tooltip>

                                <el-tooltip :content="' Beds : '+scope.row.beds" placement="top"  v-if="scope.row.beds" >
                                    <el-button size="mini" type="primary" icon="el-icon-date"></el-button>
                                </el-tooltip>

                                <el-tooltip :content="' Bathrooms : '+scope.row.bathrooms" placement="top"  v-if="scope.row.bathrooms" >
                                    <el-button size="mini" type="primary" icon="el-icon-download"></el-button>
                                </el-tooltip>
                            </el-button-group>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Status" v-permission="['manage units status']">
                        <template slot-scope="scope">
                                <el-select v-model="scope.row.status" @change="handleActive2(scope.row)">
                                    <el-option v-for="(v , k) in unitStatus" :key="k" :label="v" :value="parseInt(k)"></el-option>
                                </el-select>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Created" style="width:70px">
                        <template slot-scope="scope">
                                <el-tooltip :content="scope.row.created_at" placement="top"  >
                                    <el-button size="mini" type="primary" icon="el-icon-calendar el-icon-date"></el-button>
                                </el-tooltip>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Badges" style="width:70px">
                        <template slot-scope="scope">
                            <div class="badges">
                                <span v-if="scope.row.badges.length > 0">
                                     <span v-for="bdg in scope.row.badges"><img :src="bdg.badge.photo" style="width:25px;height:25px;" :title="bdg.badge.name" /></span>
                                </span>
                                <br /> 
                                <el-button size="mini" @click="setBadge(scope.row)" title="control" type="primary" icon="el-icon-edit" > </el-button>
                            </div>
                            
                        </template>
                    </el-table-column>

                    

                    <el-table-column align="center" label="Options">
                        <template slot-scope="scope">
                                <el-button-group>

                                    <!-- Feature -->
                                    <el-tooltip v-if="scope.row.featured == 0"  :content="'Click to Feature'" placement="top" >
                                        <el-button  v-permission="['manage units status']" @click="handleFeature( scope.row , 1 )" size="mini" type="default" icon="el-icon-star-off"></el-button>
                                    </el-tooltip>
                                    <el-tooltip v-else :content="'Click to Disable'" placement="top" >
                                        <el-button v-permission="['manage units status']" @click="handleFeature( scope.row , 0 )" size="mini" type="warning" icon="el-icon-star-on"></el-button>
                                    </el-tooltip> 

                                    <!-- Status -->

                                    <el-tooltip v-if="scope.row.status == 0" :content="unitStatus[scope.row.status]+' , Click to Active'" placement="top" >
                                        <el-button v-permission="['manage units status']" @click="handleActive( scope.row , 1 )" size="mini" type="info" icon="el-icon-error"></el-button>
                                    </el-tooltip>  
                                    <el-tooltip v-else :content="unitStatus[scope.row.status]+' , Click to Disable'" placement="top" >
                                        <el-button v-permission="['manage units status']" @click="handleActive( scope.row , 0 )" size="mini" type="success" icon="el-icon-success"></el-button>
                                    </el-tooltip>  

                                    <!-- Edit -->
                                    <el-tooltip content="Edit" placement="top" >
                                        <router-link :to="'/ezuru/units/edit/'+scope.row.id" >
                                        <el-button v-permission="['manage units edit']" size="mini" type="info" icon="el-icon-edit">
                                        </el-button>
                                        </router-link>
                                    </el-tooltip>
                                    <!-- Delete -->
                                    <el-tooltip content="Delete" placement="top" >
                                        <el-button v-permission="['manage units delete']" size="mini" @click="handleDelete( scope.row.id , scope.row.title )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>



                            </el-button-group>
                        </template>
                    </el-table-column>

        
                    </el-table>



                <el-dialog
                    title="Badges"
                    :visible.sync="badge.show"
                    width="50%"
                >
                 <BadgeTable @reloadAgain="getBadges" :list="badges" :u_u_id="badge.u_u_id" :type="'unit'" :loading="loading" ></BadgeTable>

                </el-dialog>

                        
    </div>
</template>

<script>
    import settings from '@/settings' ;
    import UnitResource from '@/api/ezuru/units' ;
    import role from '@/directive/role' ;
    import permission from '@/directive/permission/index.js' ;
    const Unit = new UnitResource() ;
    const w = window ;
    import BadgesResource from '@/api/ezuru/badges';
    import BadgeTable from '@/views/ezuru/badges/components/Table' ;

    export default {
        directives : { permission , role } ,
        components : { BadgeTable } ,
        data(){
            return {
                unitStatus : settings.unitStatus ,
                backend : settings.backend ,
                'window' : w ,
                badge : {
                    type : 'unit_badge' ,
                    u_u_id : 0 ,
                    show : false
                },
                badges : []
            }
        },
        props : ['list' , 'loading' , 'reload'],
        methods : {
            setBadge(row){
                this.badge.show = true ;
                this.badge.u_u_id = row.id ;
                this.getBadges() ;
            }   , 
            async getBadges(){
                var id = this.badge.u_u_id ;
                console.log(id) ;
                const req = new BadgesResource();
                var Badges = await req.list({unit_id: id , 'type' : 'unit' }) ;
                this.badges = Badges.data ;
            },
            reloadAgain() {
                this.$emit('reloadAgain') ;
            }, 
            handleDelete(id, name) {
                let o = this ;
                this.$confirm('This will permanently delete Unit ' + name + '. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    Unit.destroy(id).then(response => {
                        this.$message({
                            type: 'success',
                            message: 'Delete completed',
                        });
                        o.reloadAgain();
                    }).catch(error => {
                    console.log(error);
                    });

                }).catch((e) => {
                    console.log(e);
                    this.$message({
                    type: 'info',
                    message: 'Delete canceled',
                    });
                });
            },
            handleActive : function(unit , status){

                        let o = this ;

                        this.$confirm('This will Update Unit Status. Continue?', 'Warning', {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                        }).then(() => {
                            Unit.active('' , status , unit.id ).then(response => {
                                    this.$message({
                                        type: 'success',
                                        message: 'Status Changed Succefully',
                                    });
                                    o.reloadAgain();
                            }).catch(error => {
                                    console.log(error);
                                    this.$message({ type: 'danger', message: error });     
                            });
                        }).catch((e) => {
                            this.$message({ type: 'info', message: 'Update canceled'});          
                        });

            } ,
            handleActive2 : function(unit){
                this.handleActive( unit , unit.status ) ;
            } ,
handleFeature : function(unit , status){

                        let o = this ;

                        this.$confirm('This will Update Unit Feature Status. Continue?', 'Warning', {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                        }).then(() => {
                            Unit.feature( unit.id , status ).then(response => {
                                    this.$message({
                                        type: 'success',
                                        message: 'Feature Status Changed Succefully',
                                    });
                                    o.reloadAgain();
                            }).catch(error => {
                                    console.log(error);
                                    this.$message({ type: 'danger', message: error });     
                            });
                        }).catch((e) => {
                            this.$message({ type: 'info', message: 'Update canceled'});          
                        });

            } ,
    

        }
    }
</script>
