<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'category',
        'title',
        'subtitle',
        'slug',
        'description',
        'body_content',
        'thumbnail_path',
        'thumbnail_type',
        'thumbnail_images',
        'thumbnail_video_path',
        'main_media_type',
        'main_video_path',
        'main_images',
        'main_image_path',
        'use_custom_thumbnail',
        'video_loop_start',
        'video_loop_end',
        'full_video_url',
        'embed_url',
        'media_type',
        'video_url',
        'tags',
        'client',
        'role',
        'year',
        'date_published',
        'medium',
        'collaborators',
        'demo_url',
        'github_url',
        'featured',
        'is_best_work',
        'featured_thumbnail',
        'gallery_images',
        'is_archived',
        'is_top',
        'show_story',
        'coming_soon_gallery',
        'coming_soon_gallery_ratio',
    ];

    protected $casts = [
        'featured'        => 'boolean',
        'is_best_work'    => 'boolean',
        'is_archived'     => 'boolean',
        'is_top'          => 'boolean',
        'use_custom_thumbnail' => 'boolean',
        'gallery_images'  => 'array',
        'thumbnail_images'=> 'array',
        'main_images'     => 'array',
        'video_loop_start'=> 'float',
        'video_loop_end'  => 'float',
        'show_story'      => 'boolean',
        'coming_soon_gallery' => 'array',
    ];

    /**
     * Determine if the project has meaningful body content.
     */
    public function hasBodyContent(): bool
    {
        if (empty($this->body_content)) {
            return false;
        }

        $blocks = json_decode($this->body_content, true);
        if (!is_array($blocks)) {
            return false;
        }

        return collect($blocks)->reject(function ($block) {
            $type = $block['type'] ?? '';
            
            // Text-based blocks
            if (in_array($type, ['paragraph', 'heading2', 'heading3', 'quote'])) {
                // Remove HTML tags and check if anything is left (trim whitespace & non-breaking spaces)
                $text = trim(str_replace('&nbsp;', '', strip_tags($block['content'] ?? '')));
                return empty($text);
            }
            
            // Media-based blocks
            if (in_array($type, ['image', 'video'])) {
                return empty($block['src'] ?? '');
            }
            
            // Unknown or other blocks: assume empty if they have no text content and no src
            return empty($block['content'] ?? '') && empty($block['src'] ?? '');
        })->isNotEmpty();
    }
}
