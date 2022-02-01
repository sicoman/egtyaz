<template>
    <div id="PaymentsTable">
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Score">
                        <template slot-scope="scope">
                                <star-rating v-if="scope.row.score != null" v-bind:increment="0.1" :read-only="true"  v-model="scope.row.score" :max-rating="5" inactive-color="#000" active-color="#F90" :star-size="15"></star-rating>
                                <span v-else >Not Rated Yet</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Guest Score">
                        <template slot-scope="scope">
                                <star-rating v-if="scope.row.guest_score != null" v-bind:increment="0.1" :read-only="true"  v-model="scope.row.guest_score" :max-rating="5" inactive-color="#000" active-color="#F90" :star-size="15"></star-rating>
                                <span v-else >Not Rated Yet</span>
                        </template>
                    </el-table-column>



                    <el-table-column align="center" label="Unit">
                        <template slot-scope="scope">
                                <router-link :to="'/ezuru/units/edit/'+scope.row.unit.id">{{ scope.row.unit.title }}</router-link>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Owner">
                        <template slot-scope="scope">
                                <router-link :to="'/users/user/edit/'+scope.row.owner.id"><span>{{ scope.row.owner.name }}</span></router-link>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Guest">
                        <template slot-scope="scope">
                                <router-link :to="'/users/user/edit/'+scope.row.reviewer.id"><span>{{ scope.row.reviewer.name }}</span></router-link>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Booking Date">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip :content="'Booking Start '+scope.row.booking.date_start">
                                        <el-button size="mini" type="success" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                    <el-tooltip :content="'Booking End '+scope.row.booking.date_start">
                                        <el-button size="mini" type="danger" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                    <el-tooltip :content="'Payment At '+scope.row.created_at">
                                        <el-button size="mini" type="primary" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                </el-button-group>
                        </template>
                    </el-table-column>
<!--
                    <el-table-column align="center" label="Period">
                        <template slot-scope="scope">
                                <span>{{ dayDiff( scope.row.booking ) }} Day</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Amount">
                        <template slot-scope="scope">
                                <el-tooltip :content="scope.row.booking.day_price+' per day + '+scope.row.booking.fee+' Fee'" >
                                    <span>{{ scope.row.booking.price }}</span>
                                </el-tooltip>
                        </template>
                    </el-table-column>
-->
                    
                    <el-table-column align="center" label="Status">
                        <template slot-scope="scope">
                                <el-select v-model="scope.row.status" @change="handleActive(scope.row)">
                                    <el-option v-for="(v , k) in Status" :key="k" :label="v" :value="parseInt(k)"></el-option>
                                </el-select>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Delete">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip content="View" placement="top" >
                                        <el-button size="mini" @click="handleView( scope , scope.row.title )" type="warning" icon="el-icon-view"></el-button>
                                    </el-tooltip>
                                    <el-tooltip content="Delete" placement="top" >
                                        <el-button size="mini" @click="handleDelete( scope.row.id , scope.row.title )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>
                            </el-button-group>
                        </template>
                    </el-table-column>
                    </el-table>

                    <el-dialog title="View Rating" :visible.sync="dialogVisible" width="100%" :before-close="handleClose">
                        <el-row>
                            <el-col :span="11">
                                    <table style="width:100%;border:1px solid #000" align="left" class="table-bordered" >
                                        <caption>Unit Rating</caption>
                                        <thead>
                                            <tr>
                                                <td colspan="3"><el-alert type="warning">{{sc.review}}</el-alert></td>    
                                            </tr>
                                            <tr>
                                                <th>Type</th>
                                                <th>Rating</th>
                                                
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="review in sc.reviews">
                                                <td>{{ review.type }}</td>
                                                <td><star-rating v-model="review.score"  :increment="0.1" :read-only="true" :max-rating="5" inactive-color="#000" active-color="#F90" :star-size="15"></star-rating>
</td>
                                                <td>{{review.note}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </el-col>
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="11">
                                    <table style="width:100%;border:1px solid #000" align="left" >
                                        <caption>Guest Rating</caption>
                                        <thead>
                                            <tr>
                                                <td colspan="3"><el-alert type="warning">{{sc.guest_review}}</el-alert></td>    
                                            </tr>
                                            <tr>
                                                <th>Type</th>
                                                <th>Rating</th>

                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="review in sc.guest_reviews">
                                                <td>{{ review.type }}</td>
                                                <td><star-rating v-model="review.score"  :increment="0.1" :read-only="true" :max-rating="5" inactive-color="#000" active-color="#F90" :star-size="15"></star-rating>
</td>
                                                
                                                <td>{{review.note}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </el-col>
                        </el-row>
                        
                        <span slot="footer" class="dialog-footer">
                            <el-button @click="dialogVisible = false">Close</el-button>
                        </span>
                    </el-dialog>
    </div>
</template>

<script>
    import settings from '@/settings' ;
    import ReviewsResource from '@/api/ezuru/reviews' ;
    const Reviews = new ReviewsResource() ;

    import StarRating from 'vue-star-rating';

    const Vue = window.vue;


    export default {
        data(){
            return {
                Status : settings.reviewsStatus ,
                sc : {} ,
                html : '',
                dialogVisible : false
            }
        },
        components: { StarRating } ,
        props : ['list' , 'loading' , 'reload'],
        methods : {
            reloadAgain() {
                this.$emit('reloadAgain') ;
            }, 
            handleView(scope, name) {
                let sc = scope.row ;
                this.sc = sc ;
                this.dialogVisible = true ;
            },
            handleClose(){
                this.dialogVisible = false;
            },
            handleDelete(id, name) {
                let o = this ;
                this.$confirm('This will permanently delete Payment ' + id + '. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    Reviews.destroy(id).then(response => {
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
            handleActive : function(booking){

                        let o = this ;

                        this.$confirm('This will Update Unit Status. Continue?', 'Warning', {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                        }).then(() => {
                            Reviews.active('' , booking.status , booking.id ).then(response => {
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

            },
            dayDiff(row){
                const date1 = new Date( row.date_start );
                const date2 = new Date( row.date_end );
                const diffTime = Math.abs( date2.getTime() - date1.getTime() );
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            }
        },
        computed : {
            
        }
    }
</script>
