<?php
/**
 * Copyright 2013 Eric D. Hough (http://ehough.com)
 *
 * This file is part of shortstop (https://github.com/ehough/shortstop)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Deflates data according to RFC 1952. Simulation instead of native.
 */
class ehough_shortstop_impl_decoding_content_command_SimulatedGzipDecompressingCommand extends ehough_shortstop_impl_decoding_content_command_AbstractContentDecompressingCommand
{
    /**
     * @var ehough_epilog_Logger
     */
    private $_logger;

    /**
     * Get the uncompressed version of the given data.
     *
     * @param string $compressed The compressed data.
     *
     * @throws ehough_shortstop_api_exception_RuntimeException If we couldn't handle this.
     *
     * @return string The uncompressed data.
     */
    protected function getUncompressed($compressed)
    {
        $decompressed = $this->_gzdecode($compressed);

        if ($decompressed === false) {

            throw new ehough_shortstop_api_exception_RuntimeException('Could not decompress data with simulated gzdecode()');
        }

        return $decompressed;
    }

    /**
     * Get the "friendly" name for logging purposes.
     *
     * @return string The "friendly" name of this compression.
     */
    protected function getDecompressionName()
    {
        return 'Simulated gzip';
    }

    /**
     * Determines if this compression is available on the host system.
     *
     * @return boolean True if compression is available on the host system, false otherwise.
     */
    protected function isAvailiable()
    {
        return true;
    }

    /**
     * Get the Content-Encoding header value that this command can handle.
     *
     * @return string The Content-Encoding header value that this command can handle.
     */
    protected function getExpectedContentEncodingHeaderValue()
    {
        return 'gzip';
    }

    /**
     * http://us2.php.net/manual/en/function.gzdecode.php#82930
     */
    private function _gzdecode($message)
    {
        $messageLength  = strlen($message);
        $isDebugEnabled = $this->_logger->isHandling(ehough_epilog_Logger::DEBUG);

        /* make sure this is actually gzipped */
        if ($messageLength < 18 || strcmp(substr($message, 0, 2), "\x1f\x8b")) {

            if ($isDebugEnabled) {

                $this->_logger->debug('Not in GZIP format.');
            }

            return false;
        }

        /* grab the compression method and flags */
        $compressionMethod = ord(substr($message, 2, 1));
        $compressionFlags  = ord(substr($message, 3, 1));

        if ($compressionFlags & 31 != $compressionFlags) {

            if ($isDebugEnabled) {

                $this->_logger->debug('Reserved bits not allowed.');
            }

            return false;
        }

        /* NOTE: $mtime may be negative (PHP integer limitations) */
        $mtime        = unpack('V', substr($message, 4, 4));
        $mtime        = $mtime[1];
        $xfl          = substr($message, 8, 1);
        $os           = substr($message, 8, 1);
        $headerLength = 10;
        $extraLength  = 0;
        $extra        = '';

        if ($compressionFlags & 4) {

            /* 2-byte length prefixed EXTRA data in header */
            if ($messageLength - $headerLength - 2 < 8) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('2-byte length prefixed EXTRA data in header');
                }

                return false;
            }

            $extraLength = unpack('v', substr($message, 8, 2));
            $extraLength = $extraLength[1];

            if ($messageLength - $headerLength - 2 - $extraLength < 8) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('Invalid extra length');
                }

                return false;
            }

            $extra         = substr($message, 10, $extraLength);
            $headerLength += 2 + $extraLength;
        }

        $filenameLength = 0;
        $filename       = '';

        /* If FNAME is set, an original file name is present, terminated by a zero byte. */
        if ($compressionFlags & 8) {

            if ($messageLength - $headerLength - 1 < 8) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('C-Style string');
                }

                return false;
            }

            $filenameLength = strpos(substr($message, $headerLength), chr(0));

            if ($filenameLength === false || $messageLength - $headerLength - $filenameLength - 1 < 8) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('Invalid filename length');
                }

                return false;
            }

            $filename      = substr($message, $headerLength, $filenameLength);
            $headerLength += $filenameLength + 1;
        }

        $commentlen = 0;
        $comment    = '';

        /* If FCOMMENT is set, a zero-terminated file comment is present */
        if ($compressionFlags & 16) {

            if ($messageLength - $headerLength - 1 < 8) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('C-Style string COMMENT data in header');
                }

                return false;
            }

            $commentlen = strpos(substr($message, $headerLength), chr(0));

            if ($commentlen === false || $messageLength - $headerLength - $commentlen - 1 < 8) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('Invalid comment length');
                }

                return false;
            }

            $comment       = substr($message, $headerLength, $commentlen);
            $headerLength += $commentlen + 1;
        }

        $headercrc = '';

        /* If FHCRC is set, a CRC16 for the gzip header is present, immediately before the compressed data */
        if ($compressionFlags & 2) {

            if ($messageLength - $headerLength - 2 < 8) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('2-bytes (lowest order) of CRC32 on header present');
                }

                return false;
            }

            $calccrc   = crc32(substr($message, 0, $headerLength)) & 0xffff;
            $headercrc = unpack('v', substr($message, $headerLength, 2));
            $headercrc = $headercrc[1];

            if ($headercrc != $calccrc) {

                if ($isDebugEnabled) {

                    $this->_logger->debug('Header checksum failed.');
                }

            }

            $headerLength += 2;
        }

        $datacrc = unpack('V', substr($message, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize   = unpack('V', substr($message, -4));
        $isize   = $isize[1];

        $bodyLength = $messageLength - $headerLength - 8;

        if ($bodyLength < 1) {

            if ($isDebugEnabled) {

                $this->_logger->debug('Negative body length');
            }

            return false;
        }

        $compressedBody = substr($message, $headerLength, $bodyLength);
        $decompressed   = '';

        if ($bodyLength > 0) {

            switch ($compressionMethod) {

                case 8:
                    // Currently the only supported compression method:
                    $decompressed = gzinflate($compressedBody, null);
                    break;

                default:

                    if ($isDebugEnabled) {

                        $this->_logger->debug('Unknown compression method.');
                    }

                    return false;
            }
        }

        $crc   = sprintf("%u", crc32($decompressed));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($decompressed);

        if (!$lenOK || !$crcOK) {

            return false;
        }

        return $decompressed;
    }

    /**
     * @return ehough_epilog_psr_LoggerInterface
     */
    protected function getLogger()
    {
        if (! isset($this->_logger)) {

            $this->_logger = ehough_epilog_LoggerFactory::getLogger('Simulated Gzip Decompressor');
        }

        return $this->_logger;
    }
}