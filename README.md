# tab-modifier-companion
The goal of this project is to provide a way to generate icons for all environments to be used with [chrome-tab-modifier](https://github.com/sylouuu/chrome-tab-modifier)

---

## Description
_Proof of Concept_

I used  [chrome-tab-modifier](https://github.com/sylouuu/chrome-tab-modifier) to color code my development environments.
This worked fine with the provided icons when I had only one app (Drupal) and a 3 step environment.

But I recently moved to a new project where there is way more environments and I kept getting lost in my tabs.
So I wrote a little PHP script using GD library to generated all my icons.

##### Example:

_First example list of generated icons:_
<table>
    <tr>
        <td>ENV\APP</td>
        <td align="center">Drupal</td>
        <td align="center">Magento</td>
        <td align="center">Jenkins</td>
    </tr>
    <tr>
        <td>Original</td>
        <td align="center"><img src="icons/app/drupal-favicon.png"></td>
        <td align="center"><img src="icons/app/magento-favicon.png"></td>
        <td align="center"><img src="icons/app/favicon-jenkins-yellow.png"></td>
    </tr>
    <tr>
        <td>red</td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/red/drupal.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/red/US/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/drupal-US1.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/drupal-US2.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/drupal-US3.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/drupal-US4.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/drupal-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/red/EMEA/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/drupal-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/drupal-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/drupal-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/drupal-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/drupal-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/red/magento.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/red/US/magento.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/magento-US1.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/magento-US2.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/magento-US3.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/magento-US4.png" /></td>
                    <td align="center"><img src="icons_generated/red/US/magento-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/red/EMEA/magento.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/magento-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/magento-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/magento-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/magento-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/red/EMEA/magento-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <img src="icons_generated/red/jenkins.png" />
        </td>
    </tr>
    <tr>
        <td>yellow</td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/yellow/drupal.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/yellow/US/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/drupal-US1.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/drupal-US2.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/drupal-US3.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/drupal-US4.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/drupal-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/drupal-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/drupal-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/drupal-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/drupal-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/drupal-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/yellow/magento.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/yellow/US/magento.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/magento-US1.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/magento-US2.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/magento-US3.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/magento-US4.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/US/magento-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/magento.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/magento-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/magento-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/magento-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/magento-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/yellow/EMEA/magento-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <img src="icons_generated/yellow/jenkins.png" />
        </td>
    </tr>
    <tr>
        <td>green</td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/green/drupal.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/green/US/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/drupal-US1.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/drupal-US2.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/drupal-US3.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/drupal-US4.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/drupal-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/green/EMEA/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/drupal-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/drupal-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/drupal-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/drupal-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/drupal-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/green/magento.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/green/US/magento.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/magento-US1.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/magento-US2.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/magento-US3.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/magento-US4.png" /></td>
                    <td align="center"><img src="icons_generated/green/US/magento-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/green/EMEA/magento.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/magento-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/magento-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/magento-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/magento-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/green/EMEA/magento-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <img src="icons_generated/green/jenkins.png" />
        </td>
    </tr>
    <tr>
        <td>light_blue</td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/light_blue/drupal.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/light_blue/US/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/drupal-US1.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/drupal-US2.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/drupal-US3.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/drupal-US4.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/drupal-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/drupal.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/drupal-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/drupal-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/drupal-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/drupal-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/drupal-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <table>
                <tr>
                    <td align="center"></td>
                    <td align="center" colspan="6"><img src="icons_generated/light_blue/magento.png" /></td>
                </tr>
                <tr>
                    <td align="center">US</td>
                    <td align="center"><img src="icons_generated/light_blue/US/magento.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/magento-US1.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/magento-US2.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/magento-US3.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/magento-US4.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/US/magento-US5.png" /></td>
                </tr>
                <tr>
                    <td align="center">EMEA</td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/magento.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/magento-EMEA1.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/magento-EMEA2.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/magento-EMEA3.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/magento-EMEA4.png" /></td>
                    <td align="center"><img src="icons_generated/light_blue/EMEA/magento-EMEA5.png" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <img src="icons_generated/light_blue/jenkins.png" />
        </td>
    </tr>
</table>

## Changelog

- v0.0.0 : Added proof of concept code.