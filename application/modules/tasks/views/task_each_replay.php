<div class="form-group">
                <div class="col-md-12">
                    <ul class="media-list">
                        <li class="media">
                     
                            <?php foreach ($ReplyTasks as $row) : ?>
                        
                                <div style="border-color: #81BEF7;" class="media-body todo-comment">
                                    <p class="todo-comment-head">
                                        <span class="todo-comment-username"><?= $row['EMP_NO_NAME'] ?> </span> &nbsp;
                                        <span class="todo-comment-date"><?= $row['REPLY_DATE'] ?> </span>

                                     

                                    </p>


            
                                    <p  class="todo-text-color"><?= str_replace("\n", "<br/>", $row['REPLY_TEXT']) ?></p>

                                    <!-- Nested media object -->
                                    <div class="media">
                                        <div class="media-body">
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </li>
                    </ul>
                </div>
            </div></div>