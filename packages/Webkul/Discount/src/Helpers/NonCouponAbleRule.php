<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Helpers\Discount;

use Cart;

class NonCouponAbleRule extends Discount
{
    /**
     * Applies the non couponable rule on the current cart instance
     *
     * @return mixed
     */
    public function apply($code = null)
    {
        $cart = Cart::getCart();

        $applicableRules = array();

        if (auth()->guard('customer')->check()) {
            $rules = $this->cartRule->findWhere([
                'use_coupon' => 0,
                'status' => 1
            ]);
        } else {
            $rules = $this->cartRule->findWhere([
                'use_coupon' => 0,
                'is_guest' => 1,
                'status' => 1
            ]);
        }

        $alreadyAppliedRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id,
        ]);


        if (count($alreadyAppliedRule)) {
            $alreadyAppliedRule = $alreadyAppliedRule->first()->cart_rule;

            if ($alreadyAppliedRule->use_coupon) {
                return null;
            }
        }

        // time based filter
        foreach($rules as $rule) {
            $applicability = $this->checkApplicability($rule);

            if ($applicability) {
                $item = $this->leastWorthItem();

                $actionInstance = new $this->rules[$rule->action_type];

                $impact = $actionInstance->calculate($rule, $item, $cart);

                array_push($applicableRules, [
                    'rule' => $rule,
                    'impact' => $impact
                ]);
            }
        }

        if (count($applicableRules) > 1) {
            // priority criteria
            $prioritySorted = array();
            $leastPriority = 999999999999;

            foreach ($applicableRules as $applicableRule) {
                if ($applicableRule['rule']->priority <= $leastPriority) {
                    $leastPriority = $applicableRule['rule']->priority;
                    array_push($prioritySorted, $applicableRule);
                }
            }

            // end rule criteria with end rule
            $endRules = array();

            if (count($prioritySorted) > 1) {
                foreach ($prioritySorted as $prioritySortedRule) {
                    if ($prioritySortedRule['rule']->end_other_rules) {
                        array_push($endRules, $prioritySortedRule);
                    }
                }
            } else {
                $this->save(array_first($prioritySorted)['rule']);

                return $prioritySorted;
            }

            // max impact criteria with end rule
            $maxImpacts = array();

            if (count($endRules)) {
                $this->endRuleActive = true;

                if (count($endRules) == 1) {
                    $this->save(array_first($endRules)['rule']);

                    return $endRules;
                }

                $maxImpact = 0;

                foreach ($endRules as $endRule) {
                    if ($endRule['impact']['discount'] >= $maxImpact) {
                        $maxImpact = $endRule['impact']['discount'];

                        array_push($maxImpacts, $endRule);
                    }
                }

                // oldest and max impact criteria
                $leastId = 999999999999;
                $leastIdImpactIndex = 0;

                if (count($maxImpacts) > 1) {
                    foreach ($maxImpacts as $index => $maxImpactRule) {
                        if ($maxImpactRule['rule']->id < $leastId) {
                            $leastId = $maxImpactRule['rule']->id;

                            $leastIdImpactIndex = $index;
                        }
                    }

                    $this->save($maxImpacts[$leastIdImpactIndex]['rule']);

                    return $maxImpacts[$leastIdImpactIndex];
                } else {
                    $this->save(array_first($maxImpacts)['rule']);

                    return $maxImpacts;
                }
            }

            if (count($prioritySorted) > 1) {
                $maxImpact = 0;

                foreach ($prioritySorted as $prioritySortedRule) {
                    if ($prioritySortedRule['impact']['discount'] >= $maxImpact) {
                        $maxImpact = $prioritySortedRule['impact']['discount'];

                        array_push($maxImpacts, $prioritySortedRule);
                    }
                }

                // oldest and max impact criteria
                $leastId = 999999999999;
                $leastIdImpactIndex = 0;

                if (count($maxImpacts) > 1) {
                    foreach ($maxImpacts as $index => $maxImpactRule) {
                        if ($maxImpactRule['rule']->id < $leastId) {
                            $leastId = $maxImpactRule['rule']->id;

                            $leastIdImpactIndex = $index;
                        }
                    }

                    $this->save($maxImpacts[$leastIdImpactIndex]['rule']);

                    return $maxImpacts[$leastIdImpactIndex];
                } else {
                    $this->save(array_first($maxImpacts)['rule']);

                    return array_first($applicableRules)['impact'];
                }
            } else {
                $this->save(array_first($prioritySorted)['rule']);

                return $prioritySorted;
            }
        } else if (count($applicableRules) == 1) {
            $this->save(array_first($applicableRules)['rule']);

            return array_first($applicableRules)['impact'];
        } else {
            return null;
        }
    }
}