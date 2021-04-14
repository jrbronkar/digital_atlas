<?php
/**
 * This file is part of Digital Atlas.
 *
 * Digital Atlas is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Digital Atlas is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
namespace App\Widgets\TopSocialPlatforms;

use App\Widgets\TopSocialPlatforms\Models\SocialPlatform;
use Arrilot\Widgets\AbstractWidget;

/**
 * A widget displaying Top Social Platforms data
 */
class TopSocialPlatformsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $country = func_get_arg(0);
        $platforms = SocialPlatform::where('country_id', $country['id'])->get();
        $tableData = [];
        foreach ($platforms as $platform) {
            $tableData[] = [
                'platform'  =>  $platform->name,
                'average'   =>  round($platform->stats->avg('percentage'), 3),
            ];
        }
        // Sort the table data
        $average = array_column($tableData, 'average');
        array_multisort($average, \SORT_DESC, $tableData);
        return view('top-social-platforms::top_social_platforms_widget', [
            'config'    =>  $this->config,
            'tableData' =>  $tableData
        ]);
    }

    /**
     * Async and reloadable widgets are wrapped in container.
     * You can customize it by overriding this method.
     *
     * @return array
     */
    public function container()
    {
        return [
            'element'       => 'div',
            'attributes'    => 'class="widget widget-top-social-platforms" data-widget-name="Top Social Platforms"',
        ];
    }

    /**
     * Text displayed when your widget is loading async.
     *
     * @return string
     */
    public function placeholder()
    {
        return trans('top-social-platforms::widget.loading');
    }
}
