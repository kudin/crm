<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Новая задача</h2>
                              <ul class="nav navbar-right panel_toolbox"> 
                                  <li><a href="<?=TASKS_LIST_URL;?>"><i class="fa fa-arrow-left"></i> К списку задач</a></li> 
                </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content"> 
                                    <form class="form-horizontal form-label-left" data-parsley-validate="" id="demo-form2" novalidate="">

                                        <div class="form-group">
                                            <label for="first-name" class="control-label col-md-3 col-sm-3 col-xs-12">Название задачи <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" class="form-control col-md-7 col-xs-12" required="required" id="first-name" data-parsley-id="5014"><ul class="parsley-errors-list" id="parsley-id-5014"></ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="last-name" class="control-label col-md-3 col-sm-3 col-xs-12">Ответственный <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" class="form-control col-md-7 col-xs-12" required="required" name="last-name" id="last-name" data-parsley-id="8786"><ul class="parsley-errors-list" id="parsley-id-8786"></ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="middle-name">Middle Name / Initial</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" name="middle-name" class="form-control col-md-7 col-xs-12" id="middle-name" data-parsley-id="9535"><ul class="parsley-errors-list" id="parsley-id-9535"></ul>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" required="required" class="date-picker form-control col-md-7 col-xs-12" id="birthday" data-parsley-id="1107"><ul class="parsley-errors-list" id="parsley-id-1107"></ul>
                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                <button class="btn btn-primary" type="submit">Cancel</button>
                                                <button class="btn btn-success" type="submit">Submit</button>
                                            </div>
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>