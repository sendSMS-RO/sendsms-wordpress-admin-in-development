<?php
$title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'sendsms-dashboard');
?>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'sendsms-dashboard'); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
</p>
