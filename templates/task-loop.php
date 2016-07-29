<?php $tasks = &$args; ?>

<?php $task_breaker_tasks = new ThriveProjectTasksController(); ?>

<?php if ( task_breaker_can_see_project_tasks( intval ( $tasks['project_id'] ) ) ) { ?>

    <?php if ( ! empty( $tasks['results'] ) ) { ?>

        <ul class="project-tasks-results">

        <?php foreach ( $tasks['results'] as $task ) { ?>

            <?php $priority_label = $task_breaker_tasks->getPriority( $task->priority ); ?>

            <?php $completed = ''; ?>

            <?php if ( $task->completed_by != 0 ) { ?>

                <?php $completed = 'completed'; ?>

            <?php } ?>

            <?php $classes = implode( ' ', array( esc_attr( sanitize_title( $priority_label ) ), $completed ) ); ?>

            <li class="task_breaker-task-item <?php echo esc_attr( $classes ); ?>">
                <ul class="task_breaker-task-item-details">
                    <li class="priority">
                        <span>
                            <?php $priority_collection = $task_breaker_tasks->getPriorityCollection(); ?>
                            <?php echo $priority_collection[$task->priority]; ?>
                        </span>
                    </li>
                    <li class="details">
                        <h3>
                            <a href="#tasks/view/<?php echo intval( $task->id ); ?>">
                                <?php echo esc_html( stripslashes( $task->title ) ); ?>
                                 -
                                <span class="task-id"> #<?php echo intval( $task->id );?></span>
                            </a>
                        </h3>
                    </li>
                    <li class="last-user-update">

                        <div class="task-user">

                            <?php $user = get_userdata( $task->user ); ?>

                            <div class="task-members">
                                <?php
                                    $assign_users = task_breaker_parse_assigned_users( $task->assign_users );
                                ?>
                                <?php $assigned_users_count = count( $assign_users ); ?>
                                <?php $assigned_users_limit = 4; ?>

                                <ul class="task-members-items">
                                    <?php $assign_user_limited = array(); ?>
                                    <?php if ( is_array( $assign_users ) ) { ?>
                                        <?php $assign_user_limited = array_slice( $assign_users,0, 4 ); ?>
                                    <?php } ?>
                                    <?php foreach( $assign_user_limited as $assign_user ) { ?>
                                        <li class="task-members-items-item">
                                            <a title="<?php esc_attr_e( $assign_user->display_name ); ?>" href="<?php echo esc_url( bp_core_get_userlink( $assign_user->ID, false, true ) );  ?>" class="task-members-items-item-link">
                                                <?php echo get_avatar( $assign_user->ID ); ?>
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if ( $assigned_users_count >= $assigned_users_limit ) { ?>
                                        <li class="user-assign-members-more">
                                            <span>
                                                +<?php echo $assigned_users_count - $assigned_users_limit; ?>
                                            </span>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>

            </li>
        <?php } ?>
        </ul>
    <?php } else { ?>
        <div class="error" id="message">
            <p>
                <?php _e( 'No tasks found. If you\'re trying to find a task, kindly try different keywords and/or filters.', 'task_breaker' ); ?>
            </p>
        </div>
    <?php } ?>

    <?php

    $stats = $tasks['stats'];
    $total = intval( $stats['total'] );
    $perpage    = intval( $stats['perpage'] );
    $total_page = intval( $stats['total_page'] );
    $currpage   = intval( $stats['current_page'] );
    $min_page	= intval( $stats['min_page'] );
    $max_page   = intval( $stats['max_page'] );

    if ( 0 !== $total ) { ?>

        <div class="tablenav">
            <div class="tablenav-pages">
                <span class="displaying-num">
                    <?php sprintf( _n( '%s task', '%s tasks', $total, 'task_breaker' ), $total ); ?>
                </span>

                <?php if ( $total_page >= 1 ) { ?>

                    <span id="task_breaker-task-paging" class="pagination-links">

                        <a class="first-page disabled" title="<?php esc_attr_e( 'Go to the first page', 'task-breaker' );?>" href="#tasks/page/'.$min_page.'">«</a>
                        <a class="prev-page disabled" title="<?php esc_attr_e( 'Go to the previous page', 'task_breaker' );?>" href="#">‹</a>

                        <span class="paging-input">
                            <label for="task_breaker-task-current-page-selector" class="screen-reader-text">
                                <?php esc_html_e( 'Select Page', 'task_breaker' ); ?>
                            </label>
                            <input readonly class="current-page" id="task_breaker-task-current-page-selector" type="text" maxlength="<?php echo esc_attr( strlen( $total_page ) ); ?>"
                                size="<?php echo esc_attr( strlen( $total_page ) ); ?>" value="<?php echo esc_attr( intval( $currpage ) ); ?>" />

                            <?php esc_attr_e('of','task-breaker'); ?>

                            <span class="total-pages">
                                <?php echo esc_html( $total_page ); ?>
                            </span><!--.total-pages-->
                        </span><!--.paging-input-->

                        <a class="next-page" title="<?php esc_attr_e( 'Go to the next page!!!', 'task_breaker' ); ?>" href="#">›</a>
                        <a class="last-page" title="<?php esc_attr_e( 'Go to the last page', 'task_breaker' );?>" href="#tasks/page/<?php echo intval($max_page); ?>">»</a>

                    </span><!--#task_breaker-task-paging-->

                <?php } // endif $total_page >= 1 ?>

            </div><!--.tablenav-->
        </div><!--.tablenav-pages -->

    <?php } // End if ( 0 !== $total ). ?>
<?php } else { ?>
    <p class="info" id="message">
        <?php esc_attr_e('Only members of this group can see tasks.','task-breaker'); ?>
    </p>
    <p>
        <a class="button" href="#">
            Request Access @Todo
        </a>
    </p>
<?php } ?>
