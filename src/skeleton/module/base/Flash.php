<?php
namespace tuanlq11\cms\skeleton\module\base;

/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/14/16
 * Time: 1:47 PM
 */
trait Flash
{
    /**
     * Generate prefix for filter session
     */
    protected function getFilterPrefix()
    {
        $prefix = md5(sprintf("%s-%s-flash", env('APP_ENV'), $this->getModuleName()));
        return $prefix;
    }

    /**
     * Set flash error
     *
     * @param $data
     *
     * @return $this
     */
    protected function flashError($data)
    {
        Session::flash($this->getFilterPrefix() . '-error', $data);
        return $this;
    }

    /**
     * Set flash success
     *
     * @param $data
     *
     * @return $this
     */
    protected function flashSuccess($data)
    {
        Session::flash($this->getFilterPrefix() . '-success', $data);
        return $this;
    }

    /**
     * Set flash notify
     *
     * @param $data
     *
     * @return $this
     */
    protected function flashNotify($data)
    {
        Session::flash($this->getFilterPrefix() . '-notify', $data);
        return $this;
    }
}