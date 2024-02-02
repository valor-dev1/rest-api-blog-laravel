<?php

return [
    'users' => [
        'created_successfully'  => 'User is created Successfully',
        'updated_successfully'  => 'User is updated Successfully',
        'deleted_successfully'  => 'User is deleted Successfully',
        'not_allowed'           => 'You do not allowed to :action User.',
        'not_allowed_vieW_posts'    => 'You are not allowed to view posts for :user'
    ],
    'posts' => [
        'not_allowed_create'    => 'You are not allowed create a post',
        'not_allowed_update'    => 'You are not allowed update this post',
        'not_allowed_delete'    => 'You are not allowed delete this post',
        'not_allowed_force_delete'    => 'You are not allowed permanently delete a post',
        'not_allowed_restore'    => 'You are not allowed restore a post',
        'created_successfully'  => 'Post ":title" is created successfully',
        'updated_successfully'  => 'Post ":title" is updated successfully',
        'deleted_successfully'  => 'Post ":title" was deleted successfully',
    ],
    'comments'  => [
        'not_allowed_update'    => 'You are not allowed update this comment',
        'not_allowed_delete'    => 'You are not allowed delete this comment',
        'created_successfully'  => 'Comment is created successfully and is pending for review',
        'updated_successfully'  => 'Comment is updated successfully',
        'deleted_successfully'  => 'Comment was deleted successfully',
    ],
    'not_found' => 'The requested resourced not found'
];