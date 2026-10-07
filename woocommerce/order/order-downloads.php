<?php
/**
 * Downloads as a list of file cards: file type icon, file name, product, remaining / expiry and a "Download file" button.
 * Theme override of woocommerce/templates/order/order-downloads.php, used on the order received page,
 * My Account > view order and My Account > downloads. Styling: .tp-downloads in assets/scss/_general.scss.
 *
 * Extra columns from wc_get_account_downloads_columns() and the woocommerce_account_downloads_column_{id} actions still render.
 *
 * @package WooCommerce\Templates
 * @version 3.3.0
 *
 * @var array $downloads
 */

if (!defined('ABSPATH')) {
    exit;
}

$columns = wc_get_account_downloads_columns();

// Font Awesome 4 icon by file extension
$file_icons = array(
    'pdf'  => 'fa-file-pdf-o',
    'zip'  => 'fa-file-archive-o',
    'rar'  => 'fa-file-archive-o',
    '7z'   => 'fa-file-archive-o',
    'jpg'  => 'fa-file-image-o',
    'jpeg' => 'fa-file-image-o',
    'png'  => 'fa-file-image-o',
    'gif'  => 'fa-file-image-o',
    'webp' => 'fa-file-image-o',
    'svg'  => 'fa-file-image-o',
    'mp3'  => 'fa-file-audio-o',
    'wav'  => 'fa-file-audio-o',
    'mp4'  => 'fa-file-video-o',
    'mov'  => 'fa-file-video-o',
    'doc'  => 'fa-file-word-o',
    'docx' => 'fa-file-word-o',
    'xls'  => 'fa-file-excel-o',
    'xlsx' => 'fa-file-excel-o',
    'csv'  => 'fa-file-excel-o',
    'ppt'  => 'fa-file-powerpoint-o',
    'pptx' => 'fa-file-powerpoint-o',
    'txt'  => 'fa-file-text-o',
);
?>
<section class="woocommerce-order-downloads">
    <?php if (isset($show_title)) : ?>
        <h2 class="woocommerce-order-downloads__title"> <?php esc_html_e('Downloads', 'woocommerce'); ?> </h2>
    <?php endif; ?>

    <ul class="tp-downloads">
        <?php foreach ($downloads as $download) :
            $file = isset($download['file']['file']) ? $download['file']['file'] : '';
            $extension = strtolower(pathinfo((string) wp_parse_url($file, PHP_URL_PATH), PATHINFO_EXTENSION));
            $icon = isset($file_icons[$extension]) ? $file_icons[$extension] : 'fa-file-o';
        ?>
            <li class="tp-download">
                <span class="tp-download-icon" aria-hidden="true"> <i class="fa <?php echo esc_attr($icon); ?>"></i> </span>

                <div class="tp-download-info">
                    <span class="tp-download-name"> <?php echo esc_html($download['download_name']); ?> </span>

                    <span class="tp-download-product download-product">
                        <?php
                        if (has_action('woocommerce_account_downloads_column_download-product')) {
                            do_action('woocommerce_account_downloads_column_download-product', $download);
                        } elseif ($download['product_url']) {
                            echo '<a href="' . esc_url($download['product_url']) . '">' . esc_html($download['product_name']) . '</a>';
                        } else {
                            echo esc_html($download['product_name']);
                        }
                        ?>
                    </span>

                    <span class="tp-download-meta">
                        <?php if (isset($columns['download-remaining'])) : ?>
                            <span class="download-remaining">
                                <?php echo esc_html($columns['download-remaining']); ?>:
                                <strong>
                                    <?php
                                    if (has_action('woocommerce_account_downloads_column_download-remaining')) {
                                        do_action('woocommerce_account_downloads_column_download-remaining', $download);
                                    } else {
                                        echo is_numeric($download['downloads_remaining']) ? esc_html($download['downloads_remaining']) : esc_html__('&infin;', 'woocommerce');
                                    }
                                    ?>
                                </strong>
                            </span>
                        <?php endif; ?>

                        <?php if (isset($columns['download-expires'])) : ?>
                            <span class="download-expires">
                                <?php echo esc_html($columns['download-expires']); ?>:
                                <strong>
                                    <?php
                                    if (has_action('woocommerce_account_downloads_column_download-expires')) {
                                        do_action('woocommerce_account_downloads_column_download-expires', $download);
                                    } elseif (!empty($download['access_expires'])) {
                                        echo '<time datetime="' . esc_attr(date('Y-m-d', strtotime($download['access_expires']))) . '" title="' . esc_attr(strtotime($download['access_expires'])) . '">' . esc_html(date_i18n(get_option('date_format'), strtotime($download['access_expires']))) . '</time>';
                                    } else {
                                        esc_html_e('Never', 'woocommerce');
                                    }
                                    ?>
                                </strong>
                            </span>
                        <?php endif; ?>

                        <?php
                        // Columns added by plugins
                        foreach ($columns as $column_id => $column_name) {
                            if (in_array($column_id, array('download-product', 'download-remaining', 'download-expires', 'download-file'), true) || !has_action('woocommerce_account_downloads_column_' . $column_id)) {
                                continue;
                            }
                            echo '<span class="' . esc_attr($column_id) . '">';
                            do_action('woocommerce_account_downloads_column_' . $column_id, $download);
                            echo '</span>';
                        }
                        ?>
                    </span>
                </div>

                <div class="tp-download-action download-file">
                    <?php if (has_action('woocommerce_account_downloads_column_download-file')) : ?>
                        <?php do_action('woocommerce_account_downloads_column_download-file', $download); ?>
                    <?php else : ?>
                        <a href="<?php echo esc_url($download['download_url']); ?>" class="woocommerce-MyAccount-downloads-file tp-download-button" aria-label="<?php echo esc_attr(sprintf(__('Download %s', 'woocommerce'), $download['download_name'])); ?>">
                            <i class="pe-7s-download" aria-hidden="true"></i> Download file
                        </a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
