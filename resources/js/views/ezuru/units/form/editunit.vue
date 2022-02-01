<template>
    <div id="editUnit" class="app-container" v-show="canEditOrAdd">
            <h3 v-if="unit.title.trim() == '' "> Add New Unit </h3>
            <div v-else> 
                    <h3>Edit Unit {{ unit.name }}</h3>
                    <br />
                    <div id="selectStep">
                        <el-form label-position="left" label-width="150px" style="max-width:500px" >
                            <el-form-item :label="'Select Step'" >
                                <el-select label v-model="step">
                                        <el-option :value="0" label="Details"></el-option>
                                        <el-option :value="1" label="Features"></el-option>
                                        <el-option :value="2" label="Attachments"></el-option>
                                        <el-option :value="3" label="Days"></el-option>
                                        <el-option :value="4" label="Rules"></el-option>
                                        <el-option :value="5" label="Tax - Fees"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>
                    </div>
            </div>
            <br />

            <el-steps :active="step" finish-status="success" simple>
                <el-step title="Details" icon="el-icon-edit"></el-step>
                <el-step title="Features" icon="el-icon-s-operation"></el-step>
                <el-step title="Attachments" icon="el-icon-picture"></el-step>
                <el-step title="Days" icon="el-icon-date"></el-step>
                <el-step title="Rules" icon="el-icon-flag"></el-step>
                <el-step title="Tax - Fees - Save" icon="el-icon-finished"></el-step>
            </el-steps>

            

            <div id="stepForm">
        <el-form v-loading="loading" :model="unit" label-position="left" label-width="150px" style="max-width: 100%;">

                <div class="app-container" v-if="step == 0">
                                <el-row>
                                    <el-col :span="10">
                                        <el-form-item :label="'Unit Owner'" prop="user_id">
                                            <el-select filterable remote :remote-method="searchUser" v-model="unit.user_id" placeholder="Select unit owner" style="width:100%"  >
                                                    <el-option v-for="( v , k ) in users" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                            </el-select>
                                        </el-form-item>
                                    </el-col>
                                    
                                </el-row>

                                <el-row>
                                    <el-col :span="10">
                                        <el-form-item :label="'Title'" prop="title">
                                            <el-input v-model="unit.title"  />
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="2">&nbsp;</el-col>
                                    <el-col :span="10">
                                        <el-form-item :label="'Status'">
                                            <el-select v-model="unit.status" placeholder="Status" filterable >
                                                    <el-option v-for="( v , k ) in unitStatus" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                                    </el-col>
                                </el-row>

                                <el-row>
                                    <el-col :span="10">
                                        <el-form-item :label="'Description'" prop="description">
                                            <el-input type="textarea" v-model="unit.description"  />
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="2">&nbsp;</el-col>
                                    <el-col :span="10">
                                        <el-form-item :label="'Type'" prop="type">
                                            <el-select filterable v-model="unit.type" placeholder="Type"  >
                                                    <el-option v-for="(v , k) in options.category" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                                        <el-form-item v-if="subType.length > 0" :label="''" prop="type2">
                                            <el-select filterable v-model="unit.type2" placeholder="Sub Type"  >
                                                    <el-option v-for="item in subType" :key="item.id" :label="item.name" :value="parseInt(item.id)" />
                                            </el-select>
                                        </el-form-item>
                                    </el-col>
                                </el-row>


                                <el-divider><i class="el-icon-star-on"></i>    Specifications  <i class="el-icon-star-on"></i></el-divider>

                            
                                <el-row>
                                    <el-col :span="10">
                                        <el-form-item :label="'Guests'" prop="guests">
                                            <el-input-number  v-model="unit.guests"  />
                                        </el-form-item>
                                        <el-form-item :label="'Rooms'" prop="rooms">
                                            <el-input-number v-model="unit.rooms"  />
                                        </el-form-item>
                                        <el-form-item :label="'Beds'" prop="beds">
                                            <el-input-number  v-model="unit.beds"  />
                                        </el-form-item>
                                        <el-form-item :label="'Bathrooms'" prop="bathrooms">
                                            <el-input-number v-model="unit.bathrooms"  />
                                        </el-form-item>

                                        <el-form-item :label="'Balacons'" prop="balacons">
                                            <el-input-number v-model="unit.balacons"  />
                                        </el-form-item>
                                        
                                    </el-col>
                                    <el-col :span="2">&nbsp;</el-col>
                                    <el-col :span="10">
                                    
                                        <el-form-item  >
                                            <el-checkbox-group v-model="unit.options.views">
                                                <el-checkbox  class="options_boxes2"  v-for="(v,k) in options.views"  :label="parseInt(k)" :key="parseInt(k)">{{v}}</el-checkbox>
                                            </el-checkbox-group>
                                        </el-form-item>

                                        <el-divider><i class="el-icon-star-on"></i>   Facilites   <i class="el-icon-star-on"></i></el-divider>
                                        
                                        <el-form-item >
                                            <el-checkbox-group v-model="unit.options.rest">
                                                <el-checkbox  class="options_boxes2"  v-for="(v,k) in options.rest"  :label="parseInt(k)" :key="parseInt(k)">{{v}}</el-checkbox>
                                            </el-checkbox-group>
                                        </el-form-item>

                                    </el-col>
                                </el-row>

                                <el-divider><i class="el-icon-star-on"></i>  Location   <i class="el-icon-star-on"></i></el-divider>

                                <el-row>
                                    
                                    <el-col :span="10" v-if="startx === false">
                                        <!--    
                                        <el-form-item :label="'Location'" prop="description">
                                            <vue-google-autocomplete v-model="unit.city_area" types="geocode" id="map" classname="form-control el-input__inner" placeholder="Please type City" ></vue-google-autocomplete>
                                        </el-form-item>
                                        -->
 
                                        <el-form-item :label="'Country'" v-if="!disabled.country" prop="country" >
                                            <el-select v-model="unit.country" placeholder="Country" @change="getGovernment()" >
                                                    <el-option v-for="v in countries_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                            </el-select>
                                        </el-form-item>

                                        <el-form-item :label="'Government'" v-if="!disabled.government" prop="government">
                                            <el-select filterable v-model="unit.government" placeholder="Government" @change="getCity()"  >
                                                    <el-option v-for="v in government_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                            </el-select>
                                        </el-form-item>

                                        <el-form-item :label="'City'" prop="city" v-if="!disabled.city">
                                            <el-select  v-model="unit.city" placeholder="City"  @change="getArea()" >
                                                    <el-option v-for="v in cites_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                            </el-select>
                                        </el-form-item>

                                        <el-form-item :label="'Area'" prop="area" v-if="!disabled.area">
                                            <el-select  v-model="unit.area" placeholder="Area"    >
                                                    <el-option v-for="v in area_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                            </el-select>
                                        </el-form-item>

                                        <el-form-item :label="'Address'" prop="description">
                                            <vue-google-autocomplete v-model="unit.address" types="adress" id="map2" classname="form-control el-input__inner" placeholder="Please type your address"  ></vue-google-autocomplete>
                                        </el-form-item>

                                        <el-form-item :label="'Building Number'">
                                            <el-input v-model="unit.building_number"  />
                                        </el-form-item>

                                        <el-form-item :label="'Unit Number'">
                                            <el-input v-model="unit.unit_number"  />
                                        </el-form-item>

                                        <el-form-item :label="'Floor Number'">
                                            <el-input v-model="unit.floor_number"  />
                                        </el-form-item>

                                    </el-col>
                                    <el-col :span="2">&nbsp;</el-col>
                                    <el-col :span="10">
                                        <gmap-map
                                                :center="center"
                                                :zoom="10"
                                                style="width: 500px; height: 300px"
                                            >
                                            <gmap-marker :key="i" v-for="(m,i) in markers" :position="m.position" :draggable="true" :clickable="true" @click="setClick" @drag="setDrag" ></gmap-marker>
                                            </gmap-map>
                                    </el-col>
                                </el-row>


                                

                </div>

                <div class="app-container" v-if="step == 1">
                                
                        <el-divider><i class="el-icon-star-on"></i>    Choose appartement Amenities <i class="el-icon-star-on"></i></el-divider>
                        <br /><br />
                        <el-row>
                                            <el-checkbox-group v-model="unit.options.aminites">
                                                <el-checkbox  class="options_boxes2"  v-for="(v,k) in options.aminites"  :label="parseInt(k)" :key="parseInt(k)">{{v}}</el-checkbox>
                                            </el-checkbox-group>
                        </el-row>
                    
                </div>

                <div class="app-container" v-if="step == 2">
                                
                        <el-divider><i class="el-icon-star-on"></i>    Upload pictures <i class="el-icon-star-on"></i></el-divider>
                        <br /><br />
                        <el-row>
                            <el-col :span="24">
                                    <el-upload  class="upload-demo" ref="attachments" drag :show-file-list="false" :action="uploadUrl" :on-success="handleUpload"  multiple>
                                        <i class="el-icon-upload"></i>
                                        <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                        <div class="el-upload__tip" slot="tip"> <el-alert type="info">jpg/png files with a size less than 10 MB</el-alert> <br /> <br/> </div>
                                    
                                    </el-upload>
                            </el-col> 

                            <el-col :span="24">
                                    <el-table  row-key="ordr" border fit highlight-current-row :data="unit.attachments" ref="attachments" class="attach"  style="width: 100%">
                                        <el-table-column label="Shown">
                                            <template slot-scope="scope">
                                                 <input type="checkbox" value="1" v-model="unit.attachments[scope.$index].ordr"  />
                                            </template>    
                                        </el-table-column>
                                        <el-table-column label="Image">
                                            <template slot-scope="scope">
                                                 <img :src="scope.row.image" width="50" height="50" />
                                            </template>    
                                        </el-table-column>
                                        
                                        <el-table-column label="Title">
                                            <template slot-scope="scope">
                                                 <el-input v-model="unit.attachments[scope.$index].title" ></el-input>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Delete">
                                            <template slot-scope="scope">
                                                <el-button type="danger" @click="handleDelete( scope.row , scope.$index )"> <i class="el-icon-delete"></i> </el-button>
                                            </template>    
                                        </el-table-column>
                                        <!--
                                        <el-table-column>
                                            <template>
                                                    <span class="my-handle">::</span>
                                            </template>
                                        </el-table-column>
                                        -->
                                    </el-table>
                            </el-col>
<el-col :span="24">
                            <br/><br/> 
                            <el-divider><i class="el-icon-star-on"></i>  Upload Video <i class="el-icon-star-on"></i></el-divider>
                            <br/><br/> 

                            <el-upload
                                :action="uploadUrl"
                                :on-success="handleUploadV"
                                :on-remove="handleRemoveV"
                                accept=".mp4"
                                list-type="picture">
                                <p v-if="unit.video && unit.video.length > 20">
                                    <video :src="unit.video"></video>
                                </p>
                                <i v-else class="el-icon-plus"></i>
                            </el-upload>
</el-col>
                            


                        </el-row>


                    
                </div>


                <div class="app-container" v-if="step == 3">
                                
                        <el-divider><i class="el-icon-star-on"></i>  Check in & Out <i class="el-icon-star-on"></i></el-divider>
                        <br /><br />
                        <el-row>
                            <el-col :span="10">
                                <el-form-item :label="'Min Nights'">
                                   <el-input-number v-model="unit.min_days"  />
                                </el-form-item>
                            </el-col> 
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="10">
                                <el-form-item :label="'Max Nights'">
                                   <el-input-number v-model="unit.max_days"  />
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row>
                            <el-col :span="10">
                                <el-form-item :label="'Deliver Keys'">
                                   <el-input  v-model="unit.deliver_keys"  />
                                </el-form-item>
                            </el-col> 
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="10">
                                <el-form-item :label="'Get Keys'">
                                   <el-input v-model="unit.get_keys"  />
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row>
                            <el-col :span="10">
                                <el-form-item :label="'Check in'">
                                   <el-time-select v-model="unit.checkin" placeholder="Select time"></el-time-select>
                                </el-form-item>
                            </el-col> 
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="10">
                                <el-form-item :label="'Check Out'">
                                   <el-time-select v-model="unit.checkout" placeholder="Select time"></el-time-select>
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row>
                            <el-col :span="10">
                                <el-form-item :label="'Currency'">
                                   <el-select v-model="unit.currency" placeholder="Select Currency">
                                         <el-option value="egp">EGP</el-option>
                                         <el-option value="usd">USD</el-option>
                                   </el-select>
                                </el-form-item>
                            </el-col> 
                        </el-row>

                        
                        <el-row>

                                <el-col :span="12" >
                                    <el-divider><i class="el-icon-star-on"></i>  Avaliable Dates  <el-button size="mini" type="primary" @click="days.push( { 'date' : ['',''] , 'money' : 0 } )" >Add New</el-button> <i class="el-icon-star-on"></i></el-divider>
                                    <br/><br/>
                                    
                                    <el-row>
                                      <el-col :span="10"> <b>Date Start - End</b> </el-col>
                                      <el-col :span="3">
                                          <b>Price</b>
                                      </el-col>
                                      <el-col :span="1">
                                          <b>&nbsp;</b>
                                      </el-col>
                                      <el-col :span="3" style="padding-left:15px">
                                          <b>Rate</b>
                                      </el-col>
                                      <el-col :span="1">
                                          <b>&nbsp;</b>
                                      </el-col>
                                      <el-col :span="3" style="padding-left:15px">
                                          <b>Options</b>
                                      </el-col>
                                    </el-row>
                                    <hr />
                                    <el-row v-for="(day , k) in days" :key="k">
                                      <el-col :span="10">
                                          <el-date-picker :format="'yyyy-MM-dd'" v-model="days[k].date" type="daterange" start-placeholder="Start Date" end-placeholder="End Date"> </el-date-picker>
                                      </el-col>
                                      
                                      <el-col :span="3">
                                          <el-input v-model="days[k].money" @change="dateChange(days[k] , 'money')" />
                                      </el-col>

                                      <el-col :span="1">
                                          <b>&nbsp;</b>
                                      </el-col>

                                       <el-col :span="3" style="padding-left:15px">
                                           {{ getCurrencyRate( days[k].money , 'usd' , unit.currency ) }}
                                      </el-col>
                                      
                                      <el-col :span="4">
                                          <!--
                                           -->
                                           <el-button-group style="margin-left:15px">
                                                <el-button type="warning"  size="small" v-show="days[k].money>0" @click.prevent="loadDates(days[k])"> <i class="el-icon-view"></i> </el-button>
                                                <el-button type="danger"  size="small" @click="handleDeleteDay( k )"> <i class="el-icon-delete"></i> </el-button>
                                           </el-button-group>

                                      </el-col>

                                    </el-row>
                                    <br />
                                    <br />
                             </el-col>

                             <el-col :span="1">&nbsp;</el-col>

                             <el-col :span="11" style="background:#f1f1f1;padding:10px">
                                    <el-divider><i class="el-icon-star-on"></i>  Promotions  <el-button size="mini" type="primary" @click="PromoChanges.push( { 'date' :  ['' , ''] , 'percent' : 0 } )" >Add New</el-button> <i class="el-icon-star-on"></i></el-divider>
                                    <br/><br/>
                                    
                                    <el-row>
                                      <el-col :span="15"> <b>Date Start - End</b> </el-col>
                                      <el-col :span="4">
                                          <b>Percent</b>
                                      </el-col>
                                      <el-col :span="5" style="padding-left:15px">
                                          <b>Options</b>
                                      </el-col>
                                    </el-row>
                                    <hr />
                                    <el-row v-for="(day , k) in PromoChanges" :key="k">
                                      <el-col :span="15"><el-date-picker :format="'yyyy-MM-dd'" @change="PromoChange(PromoChanges[k])" v-model="PromoChanges[k].date" type="daterange" start-placeholder="Start Date" end-placeholder="End Date"> </el-date-picker>
                                      </el-col>
                                      <el-col :span="4">
                                          <el-input v-model="PromoChanges[k].percent" @change="PromoChange(PromoChanges[k])" />
                                      </el-col>
                                      <el-col :span="5">
                                           <el-button-group style="margin-left:15px">
                                                <!--
                                                    <el-button type="warning"  size="small" v-show="unit.promo[k].percent==-1" @click.prevent="loadDates(days[k])"> <i class="el-icon-view"></i> </el-button>
                                                -->
                                                <el-button type="danger"  size="small" @click="handleDeletePromo( k )"> <i class="el-icon-delete"></i> </el-button>
                                           </el-button-group>
                                      </el-col>

                                    </el-row>
                                    <br />
                                    <br />
                             </el-col>
                        </el-row>
                        

                       

                    </div>

                <div class="app-container" v-if="step == 4">
                                
                        <el-divider><i class="el-icon-star-on"></i>  Rules <i class="el-icon-star-on"></i></el-divider>
                        <br /><br />
                        <el-row>
                            <el-col :span="10">
                                <el-form-item :label="'Childrens Allowed'">
                                   <el-switch   :active-value="1" :inactive-value="0" v-model="unit.allow_childrens" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
                                </el-form-item>
                                <el-form-item v-if="unit.allow_childrens" :label="'Max Childrens'">
                                   <el-input-number v-model="unit.max_childrens" ></el-input-number>
                                </el-form-item>
                            </el-col> 
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="10">
                                <el-form-item :label="'Infants Allowed'">
                                   <el-switch   :active-value="1" :inactive-value="0" v-model="unit.allow_infants" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row>
                            <el-col :span="10">
                                <el-form-item :label="'Childrens Animals'">
                                   <el-switch   :active-value="1" :inactive-value="0" v-model="unit.allow_animals" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
                                </el-form-item>
                            </el-col> 
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="10">
                                <el-form-item :label="'Extra Allowed'">
                                   <el-switch  :active-value="1" :inactive-value="0" v-model="unit.allow_extra" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
                                </el-form-item>
                                <el-form-item v-if="unit.allow_extra" :label="'Max Extra'">
                                   <el-input-number v-model="unit.max_extra" ></el-input-number>
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-divider><i class="el-icon-star-on"></i>  Policy & Contact <i class="el-icon-star-on"></i></el-divider>
                        <br/><br/>

                        <el-row>
                                <el-col :span="8">
                                    <el-form-item :label="'Contract'">
                                        <el-upload :auto-upload=true class="upload-demo" :action="uploadUrl" :on-success="handleContract">
                                            <el-button size="small" type="primary">Select Contract</el-button>
                                        </el-upload>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="6">
                                        <el-button size="small" v-if="unit.contract_image" type="success">
                                            <a :href="unit.contract_image" donwload > Preview Contract </a>
                                        </el-button>
                                </el-col>
                                <el-col :span="6">
                                        <el-button size="small" v-if="unit.contract_image" type="danger" @click="unit.contract_image=''"  > Delete </el-button>
                                </el-col>
                                <el-col :span="24">
                                    <hr />
                                </el-col>
                                
                            
                        </el-row>


                        <el-row>
                                <el-col :span="24">
                                    <el-form-item :label="'Cancellation Police'">
                                            <el-select v-model="unit.cancle_policy" placeholder="Select Policy"  >
                                                    <el-option v-for="( v , k ) in options.cpolicy" :key="parseInt(k)" :label="v[0]" :value="parseInt(k)" />
                                            </el-select>
                                            <el-alert type="success" v-if="options.cpolicy.hasOwnProperty( unit.cancle_policy )">
                                                    {{ options.cpolicy[unit.cancle_policy][1] }}
                                            </el-alert>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="24">
                                    <br />
                                    <el-form-item :label="'Notes'">
                                            <el-input type="textarea" rows="5" v-model="unit.notes" placeholder="Other Notes" ></el-input>
                                    </el-form-item>
                                </el-col>
                        </el-row>

                </div>

                <div class="app-container" v-if="step == 5">
                        <br />
                        <el-divider><i class="el-icon-star-on"></i>  Ezuru And Tax <i class="el-icon-star-on"></i></el-divider>
                        <br/><br/>
                        <el-row>
                            <el-col :span="10">
                                <el-form-item :label="'Fee'">
                                   <el-input v-model="unit.fee"  />
                                </el-form-item>
                            </el-col> 
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="10">
                                <el-form-item :label="'Static Fee'">
                                   <el-input v-model="unit.fee_static"  />
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row>
                            <el-col :span="7">
                                <el-form-item :label="'Vat Tax'">
                                   <el-input v-model="unit.vat"  />
                                </el-form-item>
                            </el-col>
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="7">
                                <el-form-item :label="'Tourism Tax'">
                                   <el-input v-model="unit.tourism"  />
                                </el-form-item>
                            </el-col>
                            <el-col :span="1">&nbsp;</el-col>
                            <el-col :span="7">
                                <el-form-item :label="'Tourism Static'">
                                   <el-input v-model="unit.tourism_static"  />
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <br />
                        <br />
                        <el-divider><i class="el-icon-star-on"></i>  Unit Fee's <i class="el-icon-star-on"></i></el-divider>
                        <br/><br/>

                         <div v-for="(fe , k) in options.fee" :key="k">
                            <el-row v-if="usedFee.hasOwnProperty(k)">
                                <el-col :span="1"><el-checkbox :value="true" @change="ChangeFee(k)"></el-checkbox></el-col>
                                <el-col :span="5">{{ fe }}</el-col>
                                <el-col :span="1">&nbsp;</el-col>
                                <el-col :span="6" v-if="usedFee[k].fee_id"><el-input v-model="usedFee[k].amount"></el-input></el-col>
                                <el-col :span="1">&nbsp;</el-col>
                                <el-col :span="6" v-if="usedFee[k].fee_id" ><el-select v-model="usedFee[k].fee_type">
                                        <el-option :value="'percent'" label="Percent"></el-option>
                                        <el-option :value="'static'" label="Static"></el-option>
                                </el-select></el-col>
                                <el-col :span="24"><br /></el-col>
                            </el-row>
                            <el-row v-else>
                                
                                <el-col :span="1"><el-checkbox :value="false" @change="ChangeFee(k)"></el-checkbox></el-col>
                                <el-col :span="5">{{ fe }}</el-col>
                                <el-col :span="1">&nbsp;</el-col>
                                <el-col :span="6"><el-input value="" :disabled="true"></el-input></el-col>
                                <el-col :span="1">&nbsp;</el-col>
                                <el-col :span="6"><el-select value="" :disabled="true"></el-select></el-col>
                                <el-col :span="24"><br /></el-col>
                            </el-row>
                         </div>
                         <br />   


                        <br />
                        <br />
                        <div style="text-align:center">
                            <el-button type="primary" @click="saveUnit"> Save Unit </el-button>
                            &nbsp; &nbsp; &nbsp;
                            <el-button type="warning"> Cancle </el-button>
                        </div>
                </div>    

                <el-row>
                        <el-col :span="2">
                            <el-button type="warning" @click="step--" v-if="step>0"> Previous <i class="el-icon-prev"></i> </el-button>  
                        </el-col>
                        <el-col :span="20">&nbsp;</el-col>
                        <el-col :span="2">
                            <el-button type="primary" @click="step++" v-if="step<5"> Next <i class="el-icon-next"></i> </el-button>  
                        </el-col>
                </el-row>

        </el-form>  


        <el-dialog
            title="Customize your range days"
            :visible.sync="custom_days_edit"
            width="50%"
        >

        <el-table small :data="datesRange">
        <!-- A virtual column -->
        <el-table-column label="Date">
            <template slot-scope="scope">
                    <span class="text-info">{{ scope.row.date }}</span>
            </template>
        </el-table-column>

        <el-table-column label="Status">
            <template slot-scope="scope">
                    <select v-if="scope.row.status != 2" type="number" class="form-control" placeholder="0" v-model="dates[scope.row.date].status">
                        <option value="1">{{$t("Active")}}</option>
                        <option value="0">{{$t("InActive")}}</option>
                    </select>
                    <span v-else > Booked </span>
            </template>
        </el-table-column>
        
        <el-table-column label="Special Rate">
            <template slot-scope="scope">
                    <div v-if="scope.row.status == 1">
                        <el-tooltip  :content="'Rate = '+getCurrencyRate(dates[scope.row.date].price , (unit.currency == 'usd' ? 'EGP' : 'USD') , unit.currency , 1 )" >    
                            <el-input v-model="dates[scope.row.date].price" @change="ResetDateSpecial(scope.row)"></el-input>
                        </el-tooltip>
                    </div>
                    <span v-else> {{dates[scope.row.date].price}} </span>
            </template>
        </el-table-column>

        <el-table-column label="Promotion">
            <template slot-scope="scope">
                <!--
                    <el-input  v-if="scope.row.status != 2"
                    type="number"
                    class="form-control"
                    placeholder="0"
                    v-model="dates[scope.row.date].price_before"
                ></el-input>
                -->
                 <span v-if="scope.row.status != 2 && dates[scope.row.date].price_before < dates[scope.row.date].price"> 
                     {{ dates[scope.row.date].price_before.toFixed(2) }} - ( {{ calcPerc( dates[scope.row.date] ).toFixed(2) }} % OFF )
                </span>
                <span v-else="scope.row.status != 2"> 
                     
                </span>
                <span v-else > Booked </span>
            </template>
        </el-table-column>

      </el-table>
            
        </el-dialog>        

            </div>

         </div>

</template>

<script>
import UnitResource from '@/api/ezuru/units' ;
import settings from '@/settings' ;
import VueGoogleAutocomplete from 'vue-google-autocomplete';

import Sortable from 'sortablejs';

const googleApiOptions = { key: 'AIzaSyAceZt91hCfdVh6oFAXQfNVRRdlXNJJgLs' }

  import * as VueGoogleMaps from 'vue2-google-maps';
  import Vue from 'vue';
  import { setTimeout } from 'timers';
 
  Vue.use(VueGoogleMaps, {
    load: googleApiOptions
  });

const Units = new UnitResource() ;



    import TaxonomyResource from '@/api/ezuru/taxonomy' ;
    const Taxonomy = new TaxonomyResource() ;

    import role from '@/directive/role' ;
    import permission from '@/directive/permission/index.js' ;

    import {getToken} from '@/utils/auth' ;
    import {getInfo} from '@/api/auth' ;

    export default {
        directives : { permission , role } ,
        components : { VueGoogleAutocomplete },
        name : 'attachements' ,
        data(){
            return {
               canEditOrAdd : true , 
               step : 0 ,  
               unit : {
                   id : this.$route.params.id,
                   'title' : '' ,
                   'goverement' : 0,
                   'city' : 0 ,
                   'area' : 0 
               } ,
               options : {},
               subType : [],
               unitStatus : settings.unitStatus ,
               markers : [],
               uploadUrl : settings.apiUrl+'admin/upload',
               uploaded : [],
               newList : [] ,
               sortable : {},
               days : [],
               dates : [],
               countries_list : [] ,
               cites_list : [],
               government_list:[],
               area_list:[],
               loading:false,
               users : [] ,
               usedFee : {} ,
               datesRange : [] ,
               custom_days_edit : false ,
               startx : true ,// This To Disable Change Country , Government , city , area  when load but in update will change
               startChanged : false , 
               PromoChanges : [] ,
               auth : [] ,
               finished : false ,
               only : {
                    country : [] ,
                    government : [] ,
                    city : [] ,
                    area : []
                },
                disabled : {
                    country : false,
                    government : false,
                    city : false,
                    area : false
                },
                selected : {
                    country : [] ,
                    government : [] ,
                    city : [] ,
                    area : []
                },
                u_e : 0
            }
        },
        methods : {
            getCurrencyRate(price, currency, unitCurrency, debug) {

                if (price < 1) {
                    return 0;
                }
                let pr = 0;

                if (!currency) { currency = 'egp'; }
                if (!unitCurrency) { unitCurrency = 'egp'; }

                currency = currency.toString().toLowerCase();
                unitCurrency = unitCurrency.toString().toLowerCase();

                var cu_ = this.u_e ;

                if (currency == 'usd' && unitCurrency == 'usd') {
                    pr = price;
                } else if (currency == 'egp' && unitCurrency == 'usd') {
                    pr = price * cu_;
                } else if (currency == 'usd' && unitCurrency == 'egp') {
                    pr = price / cu_;
                } else {
                    pr = price * cu_;
                }

                return pr.toFixed(2) ;
            },
            ResetDateSpecial(date){
                date.price_before = date.price ;
                this.PromoChange(date);
            },
            calcPerc(date){
                var p = date.price ;
                var s = date.price_before ;
                return 100 - ( ( s * 100 ) / p ) ;
            } ,
            showPromo(d){
                console.log(d) ;
            },
            dateChange( days , inp ){
                if( days.money > 0 ){
                    this.calcDates( days.date[0] , days.date[1] , days.money , days.money_before , inp ) ;
                }
            } ,
            PromoChange( promo ){
                var o = this ;   
                Object.keys(this.dates).forEach(function(d){
                   o.dates[d].price_before = o.dates[d].price ;  
                }) ;
                this.PromoChanges.forEach(function(p){
                    if( p.percent > 0 ){
                        o.calcPromo( p.date[0] , p.date[1] , p.percent ) ;
                    }
                }) ;

            } ,
            changePromoDate(k){
                /*
                 this.PromoChanges[k].date_start = this.PromoChanges[k].date[0] ;   
                 this.PromoChanges[k].date_end   = this.PromoChanges[k].date[1] ; 
                */  
            },
            loadDates(day){
                
                var o = this ;
                var st = day.date[0] ; var en = day.date[1] ;
                 
                var dates = this.getDates( new Date(st) , new Date(en) ) ;
                let to = [] ;
                dates.forEach(function(d){
                    var mm = ("0" + (d.getMonth() + 1) ).slice(-2);
                    var dd = ("0" + d.getDate()).slice(-2);
                    var yy = d.getFullYear();
                    var d = yy+'-'+mm+'-'+dd ; 
                    to.push( d ) ;
                });

                var dates_to = [] ;

                to.forEach(function(t){
                    if( o.dates.hasOwnProperty(t) ){
                        dates_to.push( o.dates[t] ) ;
                    }
                });

                this.datesRange = dates_to ;
                this.custom_days_edit = true ;
            } ,
            calcDates(st , en , mon , promo , inp){
                var o = this ;
                var dates = this.getDates( new Date(st) , new Date(en) ) ;
                
                let to = [] ;
                dates.forEach(function(d){
                    var mm = ("0" + (d.getMonth() + 1) ).slice(-2);
                    var dd = ("0" + d.getDate()).slice(-2);
                    var yy = d.getFullYear();
                    var d = yy+'-'+mm+'-'+dd ; 
                    to.push( d ) ;
                });

                to.forEach(function(date){
                     if( ! o.dates.hasOwnProperty(date) ){
                         o.dates[date] = { 
                             'date' : date ,
                             'status' : 1 ,
                             'price' : mon ,
                             'price_before' : promo
                         };
                     }else{
                         if( inp == 'money' ) {
                             o.dates[date].price = mon ;
                         }else{
                             o.dates[date].price_before = promo ;
                         }
                     }
                });
            },
            calcPromo(st , en , percent){
                var o = this ;
                var dates = this.getDates( new Date(st) , new Date(en) ) ;
                
                let to = [] ;
                dates.forEach(function(d){
                    var mm = ("0" + (d.getMonth() + 1) ).slice(-2);
                    var dd = ("0" + d.getDate()).slice(-2);
                    var yy = d.getFullYear();
                    var d = yy+'-'+mm+'-'+dd ; 
                    to.push( d ) ;
                });

                to.forEach(function(date){
                   if( o.dates.hasOwnProperty(date) ){
                        var price_before = o.dates[date].price - ( ( o.dates[date].price * percent ) / 100 ) ;
                        if( price_before > 1 ) {
                            o.dates[date].price_before = price_before ;
                        }
                   }
                });
            },
            datesRange(){},
            loadDetails(days_index){
                this.activeRange = [ this.unit.days[days_index].date_start , this.unit.days[days_index].date_end ] ;
                this.custom_days_edit = true ;
            },
            ChangeFee( k ){
                var r ;
                if( this.usedFee.hasOwnProperty(k) ){
                   delete this.usedFee[k] ;
                   r = false; 
                }else{
                    this.usedFee[k] = { 'checked' : true , 'fee_id' : k , 'amount' : '' , 'fee_type' : 'static' } ;
                    r = true ;
                }
                this.usedFee = JSON.parse( JSON.stringify(this.usedFee) ) ;
                return r  ;
            },
            getSubType(){
                var par = parseInt(this.unit.type);
                if( par == 0 || isNaN(par) ) {
                     this.subType = [] ;   
                     return false ;
                }
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/category?parent='+par)
               .then( res => res.json() )
               .then( function(res){
                     self.subType = res ; 
               });
            } ,
            n(n){
                return n > 9 ? "" + n: "0" + n;
            },
            saveUnit(){
                var o = this ;
                o.loading = true ;
                this.unit.fees = [] ;
                Object.keys(o.usedFee).forEach(function(k){
                     o.unit.fees.push(o.usedFee[k]) ;
                });

                this.unit.fees = JSON.parse( JSON.stringify(this.unit.fees) ) ;

                // Save Unit Dates In Unit Object

                this.unit.dates = this.dates ;

                // Work With Promo Changes
                var PCh = JSON.parse( JSON.stringify(this.PromoChanges) ) ;
                
                this.unit.promo = [] ;
                PCh.forEach( function(prom){
                    var s = new Date( prom.date[0]) ;
                    var e = new Date( prom.date[1]) ;

                    if( isNaN(s) || isNaN(e) ){
                        // Not Vaild Dates    
                    }else{
                        var st = s.getFullYear()+'-'+o.n(s.getMonth()+1)+'-'+o.n(s.getDate()) ;
                        var en = e.getFullYear()+'-'+o.n(e.getMonth()+1)+'-'+o.n(e.getDate()) ;
                        o.unit.promo.push( { 'date_start' : st , 'date_end' : en , 'percent' : prom.percent } ) ;
                    }

                }) ;

                Units.update( this.unit.id , this.unit ).then(response => {
                        this.$message({
                            message: 'Unit Saved ' + this.unit.title +'  has been created successfully.',
                            type: 'success',
                            duration: 5 * 1000,
                    });
                    o.loading = false  ;
                }) ;
            }, 
            async getUnit(){ 
                var Unit = await Units.get( this.unit.id ) ;
                if( Unit.unit.id && Unit.unit.id > 0 ){
                     
                    this.unit = Unit.unit ;

                    this.unit.title = this.unit.title.trim() ;

                    if( Unit.unit.type2 != '' || Unit.unit.type2 != null ){
                        this.getSubType() ;
                    }

                    if( !this.unit.options.views ){ this.unit.options.views = [] ; }
                    if( !this.unit.options.rest ){ this.unit.options.rest = [] ; }
                    if( !this.unit.options.aminites ){ this.unit.options.aminites = [] ; }

                    this.options = Unit.options ;
                    this.dates   = Unit.dates   ;

                    // Unit Days
                    this.unit.days.forEach(element => {
                          this.days.push( { 'date' : [ element.date_start , element.date_end ] , 'money' : element.day_price } )  
                    });

                
                    this.unit.promo.forEach(element => {
                          this.PromoChanges.push( { 'date' : [ element.date_start , element.date_end ] , 'percent' : element.percent } )  
                    });

                    

                    // this.usedFee
                    this.unit.fees.forEach(element => {
                          this.usedFee[element.fee_id] = element ; 
                          this.usedFee[element.fee_id]['checked'] = true ;
                    });

                }else{
                    this.unit.id = 0 ;
                    this.$message({ type: 'danger' , message: 'Unable to find this Unit' });
                    this.$router.push( '/ezuru/units/' ) ;
                }
            }
            , getAreaData : function (addressData, placeResultData, id) {
                this.unit.area_city = document.querySelector("#map").value ;
            }, getAdressData : function (addressData, placeResultData, id) {
                this.unit.address = document.querySelector("#map2").value ;
            },
            placechanged : function(){},
            setDrag(latlng){
               this.unit.longitude = latlng.latLng.lng();
               this.unit.latitude = latlng.latLng.lat();
            },
            setClick(latlng){
                console.log( latlng ) ;
            }
            ,toggleCheck(k , v , type){

                if( this.unit.options[type].includes(k) ){
                    this.unit.options[type] = this.arrayRemove( this.unit.options[type] , k ) ;
                }else{
                    this.unit.options[type].push( k ) ;
                }

            },
            arrayRemove(arr, value) {

                return arr.filter(function(ele){
                    return ele != value;
                });

            },
            handleUpload(file, response){
                this.unit.attachments.push( { "image" : response.response , title : "" } ) ;
                this.dragSort();
            },
            handleUploadV(file, response){
                this.unit.video = response.response ;
            },
            handleRemoveV(){
                this.unit.video = '' ;
            },
            handleDelete(row , index){
                this.unit.attachments.splice(index,1) ;
                this.dragSort();
            },
            handleDeleteDay(index){
                var o = this ;
                // Lets Get Day Dates
                var dates = this.getDates( new Date(this.days[index].date[0]) , new Date(this.days[index].date[1]) ) ;
                let to = [] ;
                dates.forEach(function(d){
                    var mm = ("0" + (d.getMonth() + 1) ).slice(-2);
                    var dd = ("0" + d.getDate()).slice(-2);
                    var yy = d.getFullYear();
                    var d = yy+'-'+mm+'-'+dd ; 
                    to.push( d ) ;
                });

                to.forEach(function(d){
                    delete o.dates[d] ;
                });
                this.days.splice(index,1) ;
            },
            handleDeletePromo(index){
                var o = this ;
                // Lets Get Day Dates
                var dates = this.getDates( new Date(this.PromoChanges[index].date[0]) , new Date(this.PromoChanges[index].date[1]) ) ;
                let to = [] ;
                dates.forEach(function(d){
                    var mm = ("0" + (d.getMonth() + 1) ).slice(-2);
                    var dd = ("0" + d.getDate()).slice(-2);
                    var yy = d.getFullYear();
                    var d = yy+'-'+mm+'-'+dd ; 
                    to.push( d ) ;
                });

                to.forEach(function(d){
                    o.dates[d].price_before = '' ;
                });
                this.PromoChanges.splice(index,1) ;
            },
            handleEditDay(index){
                
                var dates = this.getDates( new Date(this.days[index].date[0]) , new Date(this.days[index].date[1]) ) ;
                let to = [] ;
                dates.forEach(function(d){
                    var mm = ("0" + (d.getMonth() + 1) ).slice(-2);
                    var dd = ("0" + d.getDate()).slice(-2);
                    var yy = d.getFullYear();
                    var d = yy+'-'+mm+'-'+dd ; 
                    to.push( d ) ;
                });

                this.custom_days = to ;
                this.custom_days_edit = true ;
            },
            dragSort(){
                return false;
                const el = document.querySelector('.el-table__body tbody') ;

                if( this.sortable.hasOwnProperty('remove') ){
                    this.sortable.remove();
                }
                
                this.sortable = Sortable.create(el, {
                    ghostClass: 'sortable-ghost',
                    handle    : ".my-handle" ,
                    setData: function(dataTransfer) {
                        dataTransfer.setData('Text', '') ;
                    },
                    onEnd: evt => {
                        const targetRow = this.unit.attachments.splice(evt.oldIndex, 1)[0];
                        this.unit.attachments.splice(evt.newIndex, 0, targetRow);
                        const tempIndex = this.newList.splice(evt.oldIndex, 1)[0];
                        this.newList.splice(evt.newIndex, 0, tempIndex);
                    },
                });
            },
            converty(str) {
                var mnths = {
                        Jan: "01",
                        Feb: "02",
                        Mar: "03",
                        Apr: "04",
                        May: "05",
                        Jun: "06",
                        Jul: "07",
                        Aug: "08",
                        Sep: "09",
                        Oct: "10",
                        Nov: "11",
                        Dec: "12"
                        },
                        date = str.split(" ");

                    return [date[3], mnths[date[1]], date[2]].join("-");
            },
            calcDays(){
                var o = this ;
                o.unit.days = [] ;
                o.days.forEach( function(elem){
                    let start = elem.date[0] ;
                    let end  = elem.date[1] ;
                    if( start.toString().length > 10 ) {
                        start = o.converty(start.toString());
                    }
                    if( end.toString().length > 10 ) {
                        end = o.converty(end.toString());
                    }
                    o.unit.days.push( { "date_start" : start , "date_end" : end , "day_price" : parseInt(elem.money) , "day_price_before" : parseInt(elem.money_before) } ) ;
                    // o.calcDates( start , end , parseInt(elem.money) , parseInt(elem.money_before) ) ;
                });
            },
            handleContract(file, response){
                this.unit.contract_image =  response.response ;
            },
            async searchCountry(query) {
                    this.loading = true;
                    let self = this ;
                    this.countries_list = await Taxonomy.select( { 's' : query , 'type' : 'country' } ) ; 
                    this.loading = false;  
            },
            async searchCity(query) {
                    this.loading = true;
                    let self = this ;
                    this.cites_list = await Taxonomy.select( { 's' : query , 'type' : 'city' } ) ; 
                    this.loading = false;  
            },
            async getGovernment() {
                var qs = this.getQS('government') ;
                if( qs == '' ){
                    qs = { 'parent' : this.unit.country , 'type' : 'government' , 'filterable' : 1 } ;
                }

                this.loading = true;
                let self = this ;
                this.government_list = await Taxonomy.select( qs ) ; 
                this.cites_list = [] ;
                this.area_list = [] ;

                this.loading = false ;
                if( this.finished === true ){
                    this.unit.government = '' ;
                    this.unit.city = '' ;
                    this.unit.area = '' ;
                }
            },
            async getCountry() {
                var qs = this.getQS('country') ;
                if( qs == '' ){
                    qs = { 'parent' : 0 , 'type' : 'country' , 'filterable' : 1 } ;
                }

                this.loading = true;
                let self = this ;
                this.countries_list = await Taxonomy.select( qs ) ; 
                this.government_list = [] ;
                this.cites_list = [] ;
                this.area_list = [] ;

                this.loading = false;  
                if( this.finished === true ){
                    this.unit.country = '' ;
                    this.unit.government = '' ;
                    this.unit.city = '' ;
                    this.unit.area = '' ;
                }
            },
            async getCity() {

                var qs = this.getQS('city') ;
                if( qs == '' ){
                    qs = { 'parent' : this.unit.government , 'type' : 'city' , 'filterable' : 1 } ;
                }
                
                this.loading = true;
                let self = this ;
                this.cites_list = await Taxonomy.select( qs ) ; 
                this.area_list = [] ;

                this.loading = false ;
                if( this.finished === true ){
                    this.unit.city = '' ;
                    this.unit.area = '' ;
                }
            },
            async getArea() {
                var qs = this.getQS('area') ;
                if( qs == '' ){
                    qs = { 'parent' : this.unit.city , 'type' : 'area' , 'filterable' : 1 } ;
                }
                this.loading = true;
                let self = this ;
                this.area_list = await Taxonomy.select( qs ) ; 

                this.loading = false ;
                if( this.finished === true ){
                    this.unit.area = '' ;
                }
            },
            async searchGovernment(query) {   
                    this.loading = true;
                    let self = this ;
                    this.government_list = await Taxonomy.select( { 's' : query , 'type' : 'government' } ) ; 
                    this.loading = false;  
            },
            async searchArea(query) {
                    this.loading = true;
                    let self = this ;
                    this.area_list = await Taxonomy.select( { 's' : query , 'type' : 'area' } ) ; 
                    this.loading = false;  
            },
            searchUser(query) {
                let self = this ;
                self.users = [] ;
                if( !query && this.unit.user_id > 0 ){
                    query = this.unit.user_id ;
                }
                fetch(settings.apiUrl+'admin/users/list?s='+query)
               .then( res => res.json() )
               .then( function(res){
                     self.users = res ; 
               });
            } ,
            getDates : function(startDate, endDate) {
                    var dates = [],
                    currentDate = startDate,
                    addDays = function(days) {
                        var date = new Date(this.valueOf());
                        date.setDate(date.getDate() + days);
                        return date ;
                    } ;
                    while (currentDate <= endDate) {
                        dates.push(currentDate);
                        currentDate = addDays.call(currentDate, 1);
                    }
                return dates;
            },
            getQS(type){
                var o = this ;
                if( o.auth.roles.includes('admin') || o.auth.roles.includes('manager') ){
                  return '' ;
                }
                var id_in = '' ; var par = '' ;

                if( type == 'country' ){
                    if( o.only.country.length > 0 ){
                        o.only.country.forEach(function(cou){
                            id_in = id_in+','+cou ;
                        });
                    }
                }else if( type == 'government' ){
                    if( o.only.country.length > 0 ){
                        o.only.country.forEach(function(cou){
                            par = par+','+cou ;
                        });
                    }
                    if( o.only.government.length > 0 ){
                        o.only.government.forEach(function(cou){
                            id_in = id_in+','+cou ;
                        });
                    }
                }else if( type == 'city' ){
                    if( o.only.government.length > 0 ){
                        o.only.government.forEach(function(cou){
                            par = par+','+cou ;
                        });
                    }
                    if( o.only.city.length > 0 ){
                        o.only.city.forEach(function(cou){
                            id_in = id_in+','+cou ;
                        });
                    }
                }else if( type == 'area' ){
                    
                    if( o.only.city.length > 0 ){
                        o.only.city.forEach(function(cou){
                            par = par+','+cou ;
                        });
                    }

                    if( o.only.area.length > 0 ){
                        o.only.area.forEach(function(cou){
                            id_in = id_in+','+cou;
                        });
                    }
                }

                if( id_in == '' && par == '' ){
                    return '' ;
                }

                var retu = {
                "type" : type ,
                "id_in" : '' ,
                "parent" : "" ,
                'filterable' : 1
                } ;

                if( id_in != '' ){
                    retu["id_in"] = id_in ;
                }

                if( par != '' ){
                    retu["parent"] = par ;
                }

                return retu ;

            },
        },
        async mounted() {

            var o = this ;
            var auth =  await getInfo( getToken() ) ;
            o.auth = auth.data ;
            
            auth.data.area.forEach(function(ar){
                    o.only[ar.type].push( ar.area_id ) ;
            });


             if( o.auth.roles.includes('admin') || o.auth.roles.includes('manager') ){

            }else if( o.only.country.length == 0 && o.only.government.length == 0 && o.only.city.length == 0 && o.only.area.length == 0 ){
                     // You cant Control Or Edit Unit Here
                     o.$message({
                            message: 'Your Premission here are not True Please ask your supervisior to set your premissions.',
                            type: 'danger',
                            duration: 5 * 1000,
                    });

                    o.canEditOrAdd = false ;
                    return false ;
            }
            
            
            o.getSubType();

            o.getUnit() ;

            // Get Currency Rate
            fetch(settings.apiUrl+'settings/currency')
               .then( res => res.json() )
               .then( function(res){
                     o.u_e = res.currency.USD_EGP  ; 
            });

            setTimeout(() => {
                
                o.unit.user_id = parseInt(o.unit.user_id) ;
                o.unit.status = parseInt(o.unit.status) ;
                o.unit.type = parseInt(o.unit.type) ;
                o.unit.child_type = parseInt(o.unit.child_type) ;
                o.unit.type2 = parseInt(o.unit.child_type) ;
                o.unit.country = parseInt(o.unit.country) ;
                o.unit.government = parseInt(o.unit.government) ;
                o.unit.city = parseInt(o.unit.city) ;
                o.unit.area = parseInt(o.unit.area) ;

                if( isNaN(o.unit.type2) ){
                    o.unit.child_type = 0 ;
                    o.unit.type2 = 0 ;
                }



                let loaded = 0 ;

                o.searchUser();

                if( o.only.country.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){
                     o.disabled.country = true ; 
                }else{
                    o.getCountry(); loaded = 0 ;
                }
                
                if( o.only.government.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){
                     if( o.disabled.country === true ){ o.disabled.government = true ; } 
                     else if(loaded == 0){ o.getGovernment() ; loaded = 0 ; }
                }else if(loaded == 0){ o.getGovernment() ; loaded = 0 ; }
                
                if( o.only.city.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){
                     if( o.disabled.government === true ){o.disabled.city = true ; }
                     else if(loaded == 0){ o.getCity() ; loaded = 0 ; }
                }else if(loaded == 0){ o.getCity() ; loaded = 0 ; }
                
                if( o.only.area.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){
                     if( o.disabled.city === true ){
                          o.disabled.area = true ;
                     }else if(loaded == 0){
                         o.getArea() ; loaded = 0 ;
                     }
                }else if(loaded == 0){
                         o.getArea() ; loaded = 0 ;
                }


                





            }, 1500); 

            setTimeout(() => {
                o.startx = false ;
                o.finished = true ;
            }, 5000 );
             
                

                        
        },
        computed : {
            center(){
                this.markers = [] ;
                let cords = {lat: 31.0, lng: 31.0} ;
                if( this.unit.longitude && this.unit.longitude != 'null' ) {
                    let cords = { 'lng' : this.unit.longitude , 'lat' : this.unit.latitude } 
                }else if( navigator.geolocation ){
                        navigator.geolocation.getCurrentPosition(
                                function (position) {
                                    cords =  {lat: position.coords.latitude , lng: position.coords.longitude} ;
                                },
                                function (error) {
                                    
                                }, {
                                    enableHighAccuracy: true
                                    , timeout: 5000
                                }
                            );
                }
                this.markers.push( { position : cords } )  ; 
                return cords ;
            }
        },
        watch : {
            "startx" : function(){
                this.startChanged = true ;
                console.log('Startx Changed') ;
            },
            "unitx.country": function() {
                
                this.getGovernment();
                
                if( this.startChanged === true ) {
                    this.cites_list = [] ; this.unit.city = '' ;
                    this.area_list = [] ;this.unit.area = '' ;
                    console.log('Country Changed') ;
                }
            },
            "unitx.government": function() {
              this.getCity();
              if( this.startChanged === true ) {
                this.area_list = [] ;
                console.log('Government Changed') ;
              }
            },
            "unitx.city": function() {
              this.getArea();
            },
            'unit.type' : function(){
                this.getSubType() ;
            },
            step : function(){ 
                 var o = this ;
                 if( o.step == 2 ){
                     setTimeout( function(){
                           // o.dragSort();
                     }, 1000 );
                 }
            },
            days : {
                deep: true,
                handler() {
                     this.calcDays() ;
                }
            }
        }    
    }
</script>

<style>
    @import url("//unpkg.com/element-ui/lib/theme-chalk/index.css");

    .options_boxes{
        width:22%;margin:0 1.5% ;line-height:35px 
    }
    .options_boxes2{
        width:47%;margin:0 1.5% ;line-height:35px 
    }
    .el-upload,.el-upload-dragger,.el-select{width:100% !important;}


    .my-handle {
	cursor: move;
	cursor: -webkit-grabbing;
}
.el-date-editor--daterange.el-input__inner {
width: 250px;
}

</style>
